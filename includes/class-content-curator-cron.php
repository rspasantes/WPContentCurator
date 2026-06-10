<?php
/**
 * content_curator_Cron
 *
 * Manages WP-Cron scheduling for periodic Facebook post fetching.
 * Registers a custom 12-hour interval and handles the fetch callback.
 *
 * @package WP_Content_Curator
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class content_curator_Cron {

    /**
     * The cron hook name.
     */
    const CRON_HOOK = 'content_curator_fetch_event';

    /**
     * The custom schedule interval key.
     */
    const SCHEDULE_KEY = 'content_curator_twelve_hours';

    /**
     * Initialize the cron system.
     *
     * Registers the custom interval and attaches the fetch callback.
     * Called on plugins_loaded.
     *
     * @return void
     */
    public static function init() {
        add_filter( 'cron_schedules', array( __CLASS__, 'add_custom_interval' ) );
        add_action( self::CRON_HOOK, array( __CLASS__, 'run_fetch' ) );
        add_action( 'init', array( __CLASS__, 'maybe_trigger_external_cron' ) );
    }

    /**
     * Register the custom 12-hour cron interval.
     *
     * @param array $schedules Existing cron schedules.
     * @return array Modified schedules.
     */
    public static function add_custom_interval( $schedules ) {
        $schedules[ self::SCHEDULE_KEY ] = array(
            'interval' => 12 * HOUR_IN_SECONDS, // 43200 seconds.
            'display'  => esc_html__( 'Every 12 Hours', 'wp-content-curator' ),
        );

        return $schedules;
    }

    /**
     * Schedule the fetch event on plugin activation.
     *
     * Only schedules if not already scheduled to avoid duplicates.
     *
     * @return void
     */
    public static function activate() {
        if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
            wp_schedule_event( time(), self::SCHEDULE_KEY, self::CRON_HOOK );
        }
    }

    /**
     * Clear the scheduled event on plugin deactivation.
     *
     * @return void
     */
    public static function deactivate() {
        wp_clear_scheduled_hook( self::CRON_HOOK );
    }

    /**
     * Execute the fetch operation.
     *
     * Iterates over all configured Facebook Page IDs, fetches their posts
     * via the Graph API, and inserts new records into the custom DB table.
     *
     * @return array Summary with 'fetched' count and 'errors' array.
     */
    public static function run_fetch() {
        $apify_token  = get_option( 'content_curator_apify_token', '' );
        $page_ids_raw = get_option( 'content_curator_page_ids', '' );

        if ( empty( $apify_token ) || empty( $page_ids_raw ) ) {
            error_log( '[WP FB Curator] Cron skipped: Apify API token or page URLs not configured.' );
            return array(
                'fetched' => 0,
                'errors'  => array( 'Configuration incomplete.' ),
            );
        }

        // Parse comma-separated page URLs/names, trimming whitespace.
        $page_ids = array_filter(
            array_map( 'trim', explode( ',', $page_ids_raw ) )
        );

        $total_fetched = 0;
        $errors        = array();

        foreach ( $page_ids as $page_id ) {
            $posts = content_curator_API::fetch_page_posts( $page_id, $apify_token );

            if ( is_wp_error( $posts ) ) {
                $error_message = sprintf(
                    'Page %s: %s',
                    $page_id,
                    $posts->get_error_message()
                );
                $errors[] = $error_message;
                error_log( '[WP FB Curator] ' . $error_message );
                continue;
            }

            foreach ( $posts as $post ) {
                // Convert Facebook ISO 8601 date or Unix timestamp to MySQL datetime format.
                $fb_created_at = '';
                if ( ! empty( $post['created_time'] ) ) {
                    $timestamp = is_numeric( $post['created_time'] ) ? (int) $post['created_time'] : strtotime( $post['created_time'] );
                    if ( $timestamp ) {
                        $fb_created_at = gmdate( 'Y-m-d H:i:s', $timestamp );
                    }
                }

                $inserted = content_curator_DB::insert_post(
                    array(
                        'fb_post_id'    => $post['id'] ?? '',
                        'page_name'     => $page_id,
                        'original_text' => $post['message'] ?? '',
                        'image_url'     => $post['full_picture'] ?? '',
                        'fb_created_at' => $fb_created_at,
                    )
                );

                if ( false !== $inserted ) {
                    $total_fetched++;
                }
            }
        }

        if ( $total_fetched > 0 || ! empty( $errors ) ) {
            error_log(
                sprintf(
                    '[WP FB Curator] Cron completed. %d new posts fetched. %d errors.',
                    $total_fetched,
                    count( $errors )
                )
            );
        }

        return array(
            'fetched' => $total_fetched,
            'errors'  => $errors,
        );
    }

    /**
     * Check if the request is for the external CRON and process if authorized.
     *
     * @return void Outputs JSON and exits.
     */
    public static function maybe_trigger_external_cron() {
        if ( ! isset( $_GET['content_curator_cron_trigger'] ) ) {
            return;
        }

        // Check if external cron is enabled.
        $enabled = get_option( 'content_curator_enable_external_cron', '0' );
        if ( '1' !== $enabled ) {
            wp_send_json_error( array( 'message' => 'External CRON is disabled.' ), 403 );
        }

        // Validate secret token.
        $stored_token   = get_option( 'content_curator_external_cron_token', '' );
        $provided_token = isset( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : '';

        if ( empty( $stored_token ) || $provided_token !== $stored_token ) {
            wp_send_json_error( array( 'message' => 'Invalid or missing CRON token.' ), 403 );
        }

        // Retrieve limit and status parameter overrides.
        $limit  = isset( $_GET['limit'] ) ? absint( $_GET['limit'] ) : absint( get_option( 'content_curator_external_cron_limit', 5 ) );
        $status = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : get_option( 'content_curator_external_cron_status', 'draft' );

        if ( ! in_array( $status, array( 'draft', 'publish' ), true ) ) {
            $status = 'draft';
        }

        $results = self::process_pending_posts_automatically( $limit, $status );

        wp_send_json_success( $results );
    }

    /**
     * Process pending posts automatically: optimize with AI and copy/translate for all active languages if WPML is available.
     *
     * @param int    $limit  Maximum number of posts to process.
     * @param string $status Target post status ('draft' or 'publish').
     * @return array Summary of processed items.
     */
    public static function process_pending_posts_automatically( $limit, $status ) {
        // Query pending posts (limit offset 0)
        $pending_posts = Content_Curator_DB::get_pending_posts( 'all', 'all', $limit );

        $processed = 0;
        $details   = array();

        if ( empty( $pending_posts ) ) {
            return array(
                'processed' => 0,
                'message'   => 'No pending posts found to process.',
                'details'   => $details,
            );
        }

        // Check if WPML is active and get languages.
        $wpml_active = false;
        $wpml_languages = array();
        $default_lang = 'en';
        if ( has_filter( 'wpml_active_languages' ) ) {
            $wpml_languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );
            if ( is_array( $wpml_languages ) && ! empty( $wpml_languages ) ) {
                $wpml_active = true;
                $default_lang = apply_filters( 'wpml_default_language', NULL );
                if ( empty( $default_lang ) ) {
                    $default_lang = 'en';
                }
            }
        }

        foreach ( $pending_posts as $post ) {
            $original_text = $post->original_text;
            $image_url_raw = $post->image_url;

            // Step 1: Parse image URLs
            $image_urls = array();
            if ( ! empty( $image_url_raw ) ) {
                $decoded = json_decode( $image_url_raw, true );
                if ( is_array( $decoded ) ) {
                    $image_urls = array_map( 'esc_url_raw', $decoded );
                } else {
                    $image_urls = array( esc_url_raw( $image_url_raw ) );
                }
            }
            $image_urls = array_filter( $image_urls );

            $post_details = array(
                'id'            => $post->id,
                'fb_post_id'    => $post->fb_post_id,
                'created_posts' => array(),
            );

            if ( $wpml_active && ! empty( $wpml_languages ) ) {
                // Multi-language: optimize & translate per language using AI
                
                // 1. Get optimized text for default language
                $opt_text = content_curator_API::rewrite_text( $original_text, $default_lang );
                if ( is_wp_error( $opt_text ) ) {
                    error_log( '[WP FB Curator] CRON AI Rewrite error: ' . $opt_text->get_error_message() );
                    $opt_text = $original_text; // fallback
                }

                // Parse title and body
                $lines = preg_split( '/\r\n|\r|\n/', $opt_text, 2 );
                $title = wp_strip_all_tags( $lines[0] );
                $title = preg_replace( '/^<h2[^>]*>(.*?)<\/h2>$/i', '$1', $title );
                $title = trim( $title );
                $body  = isset( $lines[1] ) ? trim( $lines[1] ) : $opt_text;

                // Insert master post in default language
                $master_post_id = wp_insert_post(
                    array(
                        'post_title'   => $title,
                        'post_content' => $body,
                        'post_status'  => $status,
                        'post_type'    => 'post',
                    ),
                    true
                );

                if ( is_wp_error( $master_post_id ) ) {
                    error_log( '[WP FB Curator] CRON Post insertion error: ' . $master_post_id->get_error_message() );
                    continue;
                }

                $post_details['created_posts'][] = array(
                    'post_id'  => $master_post_id,
                    'language' => $default_lang,
                );

                // Set language details for master post to generate trid
                $trid = null;
                if ( has_action( 'wpml_set_element_language_details' ) ) {
                    do_action(
                        'wpml_set_element_language_details',
                        array(
                            'element_id'    => $master_post_id,
                            'element_type'  => 'post_post',
                            'trid'          => null,
                            'language_code' => $default_lang,
                            'source_language_code' => null,
                        )
                    );
                    $trid = apply_filters( 'wpml_element_trid', null, $master_post_id, 'post_post' );
                }

                // Sideload images and attach to master post
                $attachment_ids = array();
                foreach ( $image_urls as $url ) {
                    $attachment_id = content_curator_Admin::sideload_image( $url, $master_post_id );
                    if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
                        $attachment_ids[] = $attachment_id;
                    }
                }

                // Set thumbnail / gallery block on master post
                if ( ! empty( $attachment_ids ) ) {
                    set_post_thumbnail( $master_post_id, $attachment_ids[0] );

                    if ( count( $attachment_ids ) > 1 ) {
                        $gallery_html = "\n\n<!-- wp:gallery {\"linkTo\":\"none\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-default is-cropped\">";
                        foreach ( $attachment_ids as $att_id ) {
                            $img_src       = wp_get_attachment_url( $att_id );
                            $gallery_html .= "\n<!-- wp:image {\"id\":" . $att_id . ",\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n";
                            $gallery_html .= "<figure class=\"wp-block-image size-large\"><img src=\"" . esc_url( $img_src ) . "\" alt=\"\" class=\"wp-image-" . $att_id . "\"/></figure>\n";
                            $gallery_html .= "<!-- /wp:image -->\n";
                        }
                        $gallery_html .= "</figure>\n<!-- /wp:gallery -->";

                        $body .= $gallery_html;
                        wp_update_post( array(
                            'ID'           => $master_post_id,
                            'post_content' => $body,
                        ) );
                    }
                }

                // 2. Loop through other active languages, rewrite specifically and insert
                foreach ( $wpml_languages as $lang_code => $lang_info ) {
                    if ( $lang_code === $default_lang ) {
                        continue;
                    }

                    $lang_opt_text = content_curator_API::rewrite_text( $original_text, $lang_code );
                    if ( is_wp_error( $lang_opt_text ) ) {
                        error_log( '[WP FB Curator] CRON AI Rewrite error for ' . $lang_code . ': ' . $lang_opt_text->get_error_message() );
                        // fallback to master text but stripped of any gallery block
                        $lang_opt_text = get_post_field( 'post_content', $master_post_id );
                        $lang_title    = get_the_title( $master_post_id );
                    } else {
                        // Parse title and body
                        $lang_lines = preg_split( '/\r\n|\r|\n/', $lang_opt_text, 2 );
                        $lang_title = wp_strip_all_tags( $lang_lines[0] );
                        $lang_title = preg_replace( '/^<h2[^>]*>(.*?)<\/h2>$/i', '$1', $lang_title );
                        $lang_title = trim( $lang_title );
                        $lang_opt_text = isset( $lang_lines[1] ) ? trim( $lang_lines[1] ) : $lang_opt_text;
                    }

                    // Build final body with gallery if multiple attachments
                    $lang_body = $lang_opt_text;
                    if ( count( $attachment_ids ) > 1 ) {
                        $gallery_html = "\n\n<!-- wp:gallery {\"linkTo\":\"none\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-default is-cropped\">";
                        foreach ( $attachment_ids as $att_id ) {
                            $img_src       = wp_get_attachment_url( $att_id );
                            $gallery_html .= "\n<!-- wp:image {\"id\":" . $att_id . ",\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n";
                            $gallery_html .= "<figure class=\"wp-block-image size-large\"><img src=\"" . esc_url( $img_src ) . "\" alt=\"\" class=\"wp-image-" . $att_id . "\"/></figure>\n";
                            $gallery_html .= "<!-- /wp:image -->\n";
                        }
                        $gallery_html .= "</figure>\n<!-- /wp:gallery -->";
                        $lang_body .= $gallery_html;
                    }

                    // Insert translated post
                    $translated_post_id = wp_insert_post(
                        array(
                            'post_title'   => $lang_title,
                            'post_content' => $lang_body,
                            'post_status'  => $status,
                            'post_type'    => 'post',
                        ),
                        true
                    );

                    if ( ! is_wp_error( $translated_post_id ) ) {
                        $post_details['created_posts'][] = array(
                            'post_id'  => $translated_post_id,
                            'language' => $lang_code,
                        );

                        // Set thumbnail
                        if ( ! empty( $attachment_ids ) ) {
                            set_post_thumbnail( $translated_post_id, $attachment_ids[0] );
                        }

                        // Link translation
                        if ( has_action( 'wpml_set_element_language_details' ) ) {
                            do_action(
                                'wpml_set_element_language_details',
                                array(
                                    'element_id'    => $translated_post_id,
                                    'element_type'  => 'post_post',
                                    'trid'          => $trid,
                                    'language_code' => $lang_code,
                                    'source_language_code' => $default_lang,
                                )
                            );
                        }
                    }
                }
            } else {
                // Single language post
                $opt_text = content_curator_API::rewrite_text( $original_text );
                if ( is_wp_error( $opt_text ) ) {
                    error_log( '[WP FB Curator] CRON AI Rewrite error: ' . $opt_text->get_error_message() );
                    $opt_text = $original_text; // fallback
                }

                // Parse title and body
                $lines = preg_split( '/\r\n|\r|\n/', $opt_text, 2 );
                $title = wp_strip_all_tags( $lines[0] );
                $title = preg_replace( '/^<h2[^>]*>(.*?)<\/h2>$/i', '$1', $title );
                $title = trim( $title );
                $body  = isset( $lines[1] ) ? trim( $lines[1] ) : $opt_text;

                $new_post_id = wp_insert_post(
                    array(
                        'post_title'   => $title,
                        'post_content' => $body,
                        'post_status'  => $status,
                        'post_type'    => 'post',
                    ),
                    true
                );

                if ( is_wp_error( $new_post_id ) ) {
                    error_log( '[WP FB Curator] CRON Post insertion error: ' . $new_post_id->get_error_message() );
                    continue;
                }

                $post_details['created_posts'][] = array(
                    'post_id'  => $new_post_id,
                    'language' => $default_lang,
                );

                // Sideload images and attach
                $attachment_ids = array();
                foreach ( $image_urls as $url ) {
                    $attachment_id = content_curator_Admin::sideload_image( $url, $new_post_id );
                    if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
                        $attachment_ids[] = $attachment_id;
                    }
                }

                // Set thumbnail / gallery block on new post
                if ( ! empty( $attachment_ids ) ) {
                    set_post_thumbnail( $new_post_id, $attachment_ids[0] );

                    if ( count( $attachment_ids ) > 1 ) {
                        $gallery_html = "\n\n<!-- wp:gallery {\"linkTo\":\"none\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-default is-cropped\">";
                        foreach ( $attachment_ids as $att_id ) {
                            $img_src       = wp_get_attachment_url( $att_id );
                            $gallery_html .= "\n<!-- wp:image {\"id\":" . $att_id . ",\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n";
                            $gallery_html .= "<figure class=\"wp-block-image size-large\"><img src=\"" . esc_url( $img_src ) . "\" alt=\"\" class=\"wp-image-" . $att_id . "\"/></figure>\n";
                            $gallery_html .= "<!-- /wp:image -->\n";
                        }
                        $gallery_html .= "</figure>\n<!-- /wp:gallery -->";

                        $body .= $gallery_html;
                        wp_update_post( array(
                            'ID'           => $new_post_id,
                            'post_content' => $body,
                        ) );
                    }
                }
            }

            // Mark database pending post as processed
            Content_Curator_DB::update_status( $post->id, 'processed' );
            $processed++;
            $details[] = $post_details;
        }

        return array(
            'processed' => $processed,
            'message'   => sprintf( 'Successfully processed %d posts.', $processed ),
            'details'   => $details,
        );
    }
}
