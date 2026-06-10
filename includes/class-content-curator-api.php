<?php
/**
 * content_curator_API
 *
 * Handles all external HTTP requests: Facebook Graph API for post fetching
 * and OpenAI / Anthropic APIs for AI-powered text rewriting.
 *
 * @package WP_Content_Curator
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class content_curator_API {

    /**
     * Facebook Graph API version.
     */
    const FB_API_VERSION = 'v20.0';

    /**
     * Fetch posts from a Facebook Page via the Apify Scraper API.
     *
     * @param string $page_url     The Facebook Page URL or username.
     * @param string $apify_token  Apify API Token.
     * @param int    $limit        Number of posts to fetch.
     * @return array|WP_Error Array of mapped post data on success, WP_Error on failure.
     */
    public static function fetch_page_posts( $page_url, $apify_token, $limit = 20 ) {
        if ( empty( $page_url ) || empty( $apify_token ) ) {
            return new WP_Error(
                'missing_params',
                __( 'Page URL/Name and Apify API Token are required.', 'wp-content-curator' )
            );
        }

        // Normalize URL if only a username is provided.
        $page_url = trim( $page_url );
        if ( ! preg_match( '/^https?:\/\//i', $page_url ) ) {
            $page_url = 'https://www.facebook.com/' . $page_url;
        }

        $url = 'https://api.apify.com/v2/acts/apify~facebook-posts-scraper/run-sync-get-dataset-items';
        $url = add_query_arg(
            array(
                'token' => $apify_token,
            ),
            $url
        );

        $payload = array(
            'startUrls'    => array(
                array( 'url' => $page_url ),
            ),
            'resultsLimit' => absint( $limit ),
        );

        $response = wp_remote_post(
            $url,
            array(
                'timeout' => 120, // Scraper execution may take some time.
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ),
                'body'    => wp_json_encode( $payload ),
            )
        );

        // Network-level error.
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $http_code = wp_remote_retrieve_response_code( $response );
        $body      = wp_remote_retrieve_body( $response );
        $data      = json_decode( $body, true );

        // API-level error.
        if ( $http_code !== 200 && $http_code !== 201 ) {
            $error_msg = isset( $data['error']['message'] )
                ? $data['error']['message']
                : __( 'Unknown Apify API error.', 'wp-content-curator' );

            return new WP_Error(
                'apify_api_error',
                sprintf(
                    /* translators: 1: HTTP code, 2: Error message */
                    __( 'Apify API Error (HTTP %1$d): %2$s', 'wp-content-curator' ),
                    $http_code,
                    $error_msg
                )
            );
        }

        if ( ! is_array( $data ) ) {
            return new WP_Error(
                'apify_no_data',
                __( 'Invalid response format from Apify API.', 'wp-content-curator' )
            );
        }

        // Map Apify post fields to match the format expected by the DB and UI.
        $mapped_posts = array();
        foreach ( $data as $item ) {
            // Skip items that are not posts or lack essential fields.
            if ( empty( $item['postId'] ) && empty( $item['id'] ) ) {
                continue;
            }

            $image_urls = self::extract_image_urls( $item );

            $mapped_posts[] = array(
                'id'           => $item['postId'] ?? $item['id'],
                'message'      => $item['text'] ?? $item['message'] ?? '',
                'full_picture' => ! empty( $image_urls ) ? wp_json_encode( $image_urls ) : '',
                'created_time' => $item['time'] ?? $item['date'] ?? $item['timestamp'] ?? '',
                'page_name'    => $item['pageName'] ?? $item['user']['name'] ?? basename( $page_url ),
            );
        }

        return $mapped_posts;
    }

    /**
     * Rewrite text using the configured AI provider.
     *
     * Reads the provider setting from wp_options and dispatches accordingly.
     *
     * @param string $text The original text to rewrite.
     * @return string|WP_Error The rewritten text on success, WP_Error on failure.
     */
    public static function rewrite_text( $text, $target_lang = '' ) {
        $provider = get_option( 'content_curator_ai_provider', 'openai' );
        $api_key  = get_option( 'content_curator_ai_api_key', '' );

        if ( 'wordpress_ai' !== $provider && empty( $api_key ) ) {
            return new WP_Error(
                'missing_ai_key',
                __( 'AI API key is not configured. Go to Content Curator ? Settings.', 'wp-content-curator' )
            );
        }

        if ( empty( trim( $text ) ) ) {
            return new WP_Error(
                'empty_text',
                __( 'No text provided for rewriting.', 'wp-content-curator' )
            );
        }

        switch ( $provider ) {
            case 'wordpress_ai':
                return self::rewrite_with_wordpress_ai( $text, $target_lang );
            case 'anthropic':
                return self::rewrite_with_anthropic( $text, $api_key, $target_lang );
            case 'gemini':
                return self::rewrite_with_gemini( $text, $api_key, $target_lang );
            case 'openai':
            default:
                return self::rewrite_with_openai( $text, $api_key, $target_lang );
        }
    }

    /**
     * Rewrite text using WordPress 7 Native AI API.
     *
     * @param string $text The original text.
     * @return string|WP_Error Rewritten text or WP_Error.
     */
    private static function rewrite_with_wordpress_ai( $text, $target_lang = '' ) {
        if ( ! function_exists( 'wp_ai_client_prompt' ) ) {
            return new WP_Error(
                'wordpress_ai_missing',
                __( 'WordPress Native AI is not available on this site.', 'wp-content-curator' )
            );
        }

        $system_prompt = self::get_system_prompt( $target_lang );
        $full_prompt   = $system_prompt . "\n\nText to rewrite:\n" . $text;

        $response = wp_ai_client_prompt( $full_prompt )
            ->generate_text();

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( empty( $response ) ) {
            return new WP_Error(
                'wordpress_ai_empty',
                __( 'WordPress Native AI returned an empty response.', 'wp-content-curator' )
            );
        }

        return trim( $response );
    }

    /**
     * Rewrite text using the OpenAI Chat Completions API.
     *
     * @param string $text    The original text.
     * @param string $api_key The OpenAI API key.
     * @return string|WP_Error Rewritten text or WP_Error.
     */
    private static function rewrite_with_openai( $text, $api_key, $target_lang = '' ) {
        $system_prompt = self::get_system_prompt( $target_lang );

        $payload = array(
            'model'       => 'gpt-4o-mini',
            'messages'    => array(
                array(
                    'role'    => 'system',
                    'content' => $system_prompt,
                ),
                array(
                    'role'    => 'user',
                    'content' => $text,
                ),
            ),
            'temperature' => 0.7,
        );

        $response = wp_remote_post(
            'https://api.openai.com/v1/chat/completions',
            array(
                'timeout' => 60,
                'headers' => array(
                    'Authorization' => 'Bearer ' . $api_key,
                    'Content-Type'  => 'application/json',
                ),
                'body'    => wp_json_encode( $payload ),
            )
        );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $http_code = wp_remote_retrieve_response_code( $response );
        $body      = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $http_code !== 200 ) {
            $error_msg = isset( $body['error']['message'] )
                ? $body['error']['message']
                : __( 'Unknown OpenAI API error.', 'wp-content-curator' );

            return new WP_Error( 'openai_error', $error_msg );
        }

        if ( isset( $body['choices'][0]['message']['content'] ) ) {
            return trim( $body['choices'][0]['message']['content'] );
        }

        return new WP_Error(
            'openai_no_content',
            __( 'OpenAI returned an empty response.', 'wp-content-curator' )
        );
    }

    /**
     * Rewrite text using the Anthropic Messages API.
     *
     * @param string $text    The original text.
     * @param string $api_key The Anthropic API key.
     * @return string|WP_Error Rewritten text or WP_Error.
     */
    private static function rewrite_with_anthropic( $text, $api_key, $target_lang = '' ) {
        $system_prompt = self::get_system_prompt( $target_lang );

        $payload = array(
            'model'      => 'claude-3-haiku-20240307',
            'max_tokens' => 2048,
            'system'     => $system_prompt,
            'messages'   => array(
                array(
                    'role'    => 'user',
                    'content' => $text,
                ),
            ),
            'temperature' => 0.7,
        );

        $response = wp_remote_post(
            'https://api.anthropic.com/v1/messages',
            array(
                'timeout' => 60,
                'headers' => array(
                    'x-api-key'         => $api_key,
                    'anthropic-version'  => '2023-06-01',
                    'Content-Type'       => 'application/json',
                ),
                'body'    => wp_json_encode( $payload ),
            )
        );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $http_code = wp_remote_retrieve_response_code( $response );
        $body      = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $http_code !== 200 ) {
            $error_msg = isset( $body['error']['message'] )
                ? $body['error']['message']
                : __( 'Unknown Anthropic API error.', 'wp-content-curator' );

            return new WP_Error( 'anthropic_error', $error_msg );
        }

        if ( isset( $body['content'][0]['text'] ) ) {
            return trim( $body['content'][0]['text'] );
        }

        return new WP_Error(
            'anthropic_no_content',
            __( 'Anthropic returned an empty response.', 'wp-content-curator' )
        );
    }

    /**
     * Rewrite text using the Google Gemini API.
     *
     * @param string $text    The original text.
     * @param string $api_key The Gemini API key.
     * @return string|WP_Error Rewritten text or WP_Error.
     */
    private static function rewrite_with_gemini( $text, $api_key, $target_lang = '' ) {
        $system_prompt = self::get_system_prompt( $target_lang );

        $payload = array(
            'systemInstruction' => array(
                'parts' => array(
                    array( 'text' => $system_prompt )
                )
            ),
            'contents' => array(
                array(
                    'role'  => 'user',
                    'parts' => array(
                        array( 'text' => $text )
                    )
                )
            ),
            'generationConfig' => array(
                'temperature' => 0.7,
            )
        );

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $api_key;

        $response = wp_remote_post(
            $url,
            array(
                'timeout' => 60,
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'body'    => wp_json_encode( $payload ),
            )
        );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $http_code = wp_remote_retrieve_response_code( $response );
        $body      = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $http_code !== 200 ) {
            $error_msg = isset( $body['error']['message'] )
                ? $body['error']['message']
                : __( 'Unknown Gemini API error.', 'wp-content-curator' );

            return new WP_Error( 'gemini_error', $error_msg );
        }

        if ( isset( $body['candidates'][0]['content']['parts'][0]['text'] ) ) {
            return trim( $body['candidates'][0]['content']['parts'][0]['text'] );
        }

        return new WP_Error(
            'gemini_no_content',
            __( 'Gemini returned an empty response.', 'wp-content-curator' )
        );
    }

    /**
     * Get the shared system prompt for AI rewriting.
     *
     * @return string
     */
    private static function get_system_prompt( $target_lang = '' ) {
        $default_prompt = 'You act as a professional blog writer optimized for SEO. '
            . 'Your goal is to completely rewrite the text provided to you. '
            . 'You must transform it into a short, structured, attractive article '
            . 'with a clear headline at the beginning preceded by an H2 tag. '
            . 'Do not use social media hashtags under any circumstances. '
            . 'Keep the original meaning but completely change the wording '
            . 'to avoid duplicate content penalties.';

        $custom_prompt = get_option( 'content_curator_ai_prompt', '' );
        $prompt = ! empty( trim( $custom_prompt ) ) ? trim( $custom_prompt ) : $default_prompt;

        if ( ! empty( $target_lang ) ) {
            $lang_names = array(
                'en' => 'English',
                'es' => 'Spanish',
                'fr' => 'French',
                'gl' => 'Galician',
            );
            $lang_name = $lang_names[ $target_lang ] ?? $target_lang;
            $prompt .= "\n\nCRITICAL REQUIREMENT: You MUST translate and write the entire final output in the following language: " . $lang_name . ". Ensure the output grammar and structure is natural and native to that language.";
        }

        return $prompt;
    }

    /**
     * Extract all valid unique image URLs from various potential scraper item fields.
     *
     * @param array $item The post item array from Apify scraper.
     * @return array Array of valid image URLs.
     */
    public static function extract_image_urls( $item ) {
        if ( ! is_array( $item ) ) {
            return array();
        }

        $urls = array();

        // 1. Direct string keys
        $string_keys = array(
            'image',
            'thumbnail',
            'mediaUrl',
            'photo_image',
            'thumbnailImage',
            'thumbnail_url',
            'videoThumbnail',
            'video_thumbnail_url',
            'picture',
            'full_picture',
        );

        foreach ( $string_keys as $key ) {
            if ( ! empty( $item[ $key ] ) && is_string( $item[ $key ] ) && self::is_valid_image_url( $item[ $key ] ) ) {
                $urls[] = $item[ $key ];
            }
        }

        // 2. Array fields: media, images, image_urls
        // Try 'media' array
        if ( ! empty( $item['media'] ) ) {
            if ( is_string( $item['media'] ) && self::is_valid_image_url( $item['media'] ) ) {
                $urls[] = $item['media'];
            } elseif ( is_array( $item['media'] ) ) {
                foreach ( $item['media'] as $media_item ) {
                    if ( is_string( $media_item ) && self::is_valid_image_url( $media_item ) ) {
                        $urls[] = $media_item;
                    }
                    if ( is_array( $media_item ) ) {
                        $sub_keys = array( 'url', 'thumbnail', 'image', 'src', 'link', 'thumbnailImage', 'photo_image' );
                        foreach ( $sub_keys as $sub_key ) {
                            if ( ! empty( $media_item[ $sub_key ] ) && is_string( $media_item[ $sub_key ] ) && self::is_valid_image_url( $media_item[ $sub_key ] ) ) {
                                $urls[] = $media_item[ $sub_key ];
                            }
                        }
                    }
                }
            }
        }

        // Try 'images' array
        if ( ! empty( $item['images'] ) && is_array( $item['images'] ) ) {
            foreach ( $item['images'] as $img ) {
                if ( is_string( $img ) && self::is_valid_image_url( $img ) ) {
                    $urls[] = $img;
                }
                if ( is_array( $img ) && ! empty( $img['url'] ) && is_string( $img['url'] ) && self::is_valid_image_url( $img['url'] ) ) {
                    $urls[] = $img['url'];
                }
            }
        }

        // Try 'image_urls' array
        if ( ! empty( $item['image_urls'] ) && is_array( $item['image_urls'] ) ) {
            foreach ( $item['image_urls'] as $img ) {
                if ( is_string( $img ) && self::is_valid_image_url( $img ) ) {
                    $urls[] = $img;
                }
            }
        }

        // Try 'attachments' array
        if ( ! empty( $item['attachments'] ) && is_array( $item['attachments'] ) ) {
            foreach ( $item['attachments'] as $attachment ) {
                if ( ! is_array( $attachment ) ) {
                    continue;
                }
                // Check nested media -> image -> src
                if ( ! empty( $attachment['media']['image']['src'] ) && is_string( $attachment['media']['image']['src'] ) && self::is_valid_image_url( $attachment['media']['image']['src'] ) ) {
                    $urls[] = $attachment['media']['image']['src'];
                }
                // Check nested media -> image -> url
                if ( ! empty( $attachment['media']['image']['url'] ) && is_string( $attachment['media']['image']['url'] ) && self::is_valid_image_url( $attachment['media']['image']['url'] ) ) {
                    $urls[] = $attachment['media']['image']['url'];
                }
                // Check general media subkey
                if ( ! empty( $attachment['media'] ) && is_array( $attachment['media'] ) ) {
                    if ( ! empty( $attachment['media']['url'] ) && is_string( $attachment['media']['url'] ) && self::is_valid_image_url( $attachment['media']['url'] ) ) {
                        $urls[] = $attachment['media']['url'];
                    }
                    if ( ! empty( $attachment['media']['src'] ) && is_string( $attachment['media']['src'] ) && self::is_valid_image_url( $attachment['media']['src'] ) ) {
                        $urls[] = $attachment['media']['src'];
                    }
                }
            }
        }

        return array_values( array_unique( $urls ) );
    }

    /**
     * Helper to extract the first valid image URL from various potential scraper item fields.
     *
     * @param array $item The post item array from Apify scraper.
     * @return string Extracted image URL, or empty string.
     */
    private static function extract_image_url( $item ) {
        if ( ! is_array( $item ) ) {
            return '';
        }

        // 1. Direct string keys
        $string_keys = array(
            'image',
            'thumbnail',
            'mediaUrl',
            'photo_image',
            'thumbnailImage',
            'thumbnail_url',
            'videoThumbnail',
            'video_thumbnail_url',
            'picture',
            'full_picture',
        );

        foreach ( $string_keys as $key ) {
            if ( ! empty( $item[ $key ] ) && is_string( $item[ $key ] ) && self::is_valid_image_url( $item[ $key ] ) ) {
                return $item[ $key ];
            }
        }

        // 2. Array fields: media, images, image_urls
        // Try 'media' array
        if ( ! empty( $item['media'] ) ) {
            if ( is_string( $item['media'] ) && self::is_valid_image_url( $item['media'] ) ) {
                return $item['media'];
            } elseif ( is_array( $item['media'] ) ) {
                foreach ( $item['media'] as $media_item ) {
                    if ( is_string( $media_item ) && self::is_valid_image_url( $media_item ) ) {
                        return $media_item;
                    }
                    if ( is_array( $media_item ) ) {
                        $sub_keys = array( 'url', 'thumbnail', 'image', 'src', 'link', 'thumbnailImage', 'photo_image' );
                        foreach ( $sub_keys as $sub_key ) {
                            if ( ! empty( $media_item[ $sub_key ] ) && is_string( $media_item[ $sub_key ] ) && self::is_valid_image_url( $media_item[ $sub_key ] ) ) {
                                return $media_item[ $sub_key ];
                            }
                        }
                    }
                }
            }
        }

        // Try 'images' array
        if ( ! empty( $item['images'] ) && is_array( $item['images'] ) ) {
            foreach ( $item['images'] as $img ) {
                if ( is_string( $img ) && self::is_valid_image_url( $img ) ) {
                    return $img;
                }
                if ( is_array( $img ) && ! empty( $img['url'] ) && is_string( $img['url'] ) && self::is_valid_image_url( $img['url'] ) ) {
                    return $img['url'];
                }
            }
        }

        // Try 'image_urls' array
        if ( ! empty( $item['image_urls'] ) && is_array( $item['image_urls'] ) ) {
            foreach ( $item['image_urls'] as $img ) {
                if ( is_string( $img ) && self::is_valid_image_url( $img ) ) {
                    return $img;
                }
            }
        }

        // Try 'attachments' array
        if ( ! empty( $item['attachments'] ) && is_array( $item['attachments'] ) ) {
            foreach ( $item['attachments'] as $attachment ) {
                if ( ! is_array( $attachment ) ) {
                    continue;
                }
                // Check nested media -> image -> src
                if ( ! empty( $attachment['media']['image']['src'] ) && is_string( $attachment['media']['image']['src'] ) && self::is_valid_image_url( $attachment['media']['image']['src'] ) ) {
                    return $attachment['media']['image']['src'];
                }
                // Check nested media -> image -> url
                if ( ! empty( $attachment['media']['image']['url'] ) && is_string( $attachment['media']['image']['url'] ) && self::is_valid_image_url( $attachment['media']['image']['url'] ) ) {
                    return $attachment['media']['image']['url'];
                }
                // Check general media subkey
                if ( ! empty( $attachment['media'] ) && is_array( $attachment['media'] ) ) {
                    if ( ! empty( $attachment['media']['url'] ) && is_string( $attachment['media']['url'] ) && self::is_valid_image_url( $attachment['media']['url'] ) ) {
                        return $attachment['media']['url'];
                    }
                    if ( ! empty( $attachment['media']['src'] ) && is_string( $attachment['media']['src'] ) && self::is_valid_image_url( $attachment['media']['src'] ) ) {
                        return $attachment['media']['src'];
                    }
                }
            }
        }

        return '';
    }

    /**
     * Check if a URL appears to be a direct image resource rather than an HTML page.
     *
     * @param string $url The URL to validate.
     * @return bool True if it looks like a valid image resource, false otherwise.
     */
    private static function is_valid_image_url( $url ) {
        if ( empty( $url ) || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
            return false;
        }

        $host = wp_parse_url( $url, PHP_URL_HOST );
        if ( ! $host ) {
            return false;
        }

        // Exclude any links hosted on main Facebook domains (which are HTML viewer pages, not raw assets).
        // Actual image assets are hosted on fbcdn.net domains.
        if ( preg_match( '/\b(facebook\.com|fb\.com)\b/i', $host ) ) {
            return false;
        }

        // If it's a known image CDN or has an image extension, it's valid.
        if ( stripos( $host, 'fbcdn.net' ) !== false ) {
            return true;
        }

        $path = wp_parse_url( $url, PHP_URL_PATH );
        if ( $path && preg_match( '/\.(jpe?g|png|gif|webp|bmp)$/i', $path ) ) {
            return true;
        }

        // If it doesn't match fbcdn.net or a common image extension, but it's not facebook.com,
        // exclude links containing typical web-page patterns.
        $invalid_path_patterns = array( '/posts/', '/videos/', '/groups/', '/permalink', '/pages/', '/story.php' );
        if ( $path ) {
            foreach ( $invalid_path_patterns as $pattern ) {
                if ( stripos( $path, $pattern ) !== false ) {
                    return false;
                }
            }
        }

        return true;
    }
}
