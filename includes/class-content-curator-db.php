<?php
/**
 * Content_Curator_DB
 *
 * Manages the custom database table for storing fetched Facebook posts.
 * Uses WordPress dbDelta for safe table creation and $wpdb for all queries.
 *
 * @package WP_Content_Curator
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Content_Curator_DB {

    /**
     * Get the full table name with WordPress prefix.
     *
     * @return string
     */
    public static function get_table_name() {
        global $wpdb;
        return $wpdb->prefix . 'content_curator_posts';
    }

    /**
     * Create the custom database table using dbDelta.
     *
     * Called on plugin activation. dbDelta will also handle schema
     * updates on future plugin upgrades if columns are added.
     *
     * @return void
     */
    public static function create_table() {
        global $wpdb;

        $table_name      = self::get_table_name();
        $charset_collate = $wpdb->get_charset_collate();

        // dbDelta requirements:
        // - Each field on its own line.
        // - Two spaces after PRIMARY KEY.
        // - At least one KEY (index).
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            fb_post_id VARCHAR(100) NOT NULL,
            page_name VARCHAR(100) NOT NULL,
            original_text LONGTEXT DEFAULT NULL,
            image_url TEXT DEFAULT NULL,
            fetched_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            fb_created_at DATETIME NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            PRIMARY KEY  (id),
            UNIQUE KEY fb_post_id (fb_post_id),
            KEY status (status),
            KEY fb_created_at (fb_created_at)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    /**
     * Insert a new post record, skipping if fb_post_id already exists.
     *
     * @param array $data Associative array with keys: fb_post_id, page_name,
     *                    original_text, image_url, fb_created_at.
     * @return int|false The row ID on success, false on duplicate or failure.
     */
    public static function insert_post( $data ) {
        global $wpdb;

        $table_name = self::get_table_name();

        // Check for existing record to avoid duplicate key errors.
        $exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM $table_name WHERE fb_post_id = %s",
                $data['fb_post_id']
            )
        );

        if ( $exists ) {
            return false;
        }

        $image_url_raw = $data['image_url'] ?? '';
        $sanitized_image_url = '';
        if ( ! empty( $image_url_raw ) ) {
            $decoded = json_decode( $image_url_raw, true );
            if ( is_array( $decoded ) ) {
                $sanitized_array = array_map( 'esc_url_raw', $decoded );
                $sanitized_image_url = wp_json_encode( $sanitized_array );
            } else {
                $sanitized_image_url = esc_url_raw( $image_url_raw );
            }
        }

        $result = $wpdb->insert(
            $table_name,
            array(
                'fb_post_id'    => sanitize_text_field( $data['fb_post_id'] ),
                'page_name'     => sanitize_text_field( $data['page_name'] ),
                'original_text' => wp_kses_post( $data['original_text'] ),
                'image_url'     => $sanitized_image_url,
                'fb_created_at' => sanitize_text_field( $data['fb_created_at'] ),
            ),
            array( '%s', '%s', '%s', '%s', '%s' )
        );

        if ( false === $result ) {
            return false;
        }

        return $wpdb->insert_id;
    }

    /**
     * Get pending posts filtered by time range, site name, date range, and pagination.
     *
     * @param string $filter     Time filter key: '24h', '48h', '7d', or 'all'.
     * @param string $site       Site/Page name to filter by, or 'all'.
     * @param int    $limit      Limit for pagination (0 for no limit).
     * @param int    $offset     Offset for pagination.
     * @param string $start_date Start date (YYYY-MM-DD format).
     * @param string $end_date   End date (YYYY-MM-DD format).
     * @return array Array of row objects.
     */
    public static function get_pending_posts( $filter = 'all', $site = 'all', $limit = 0, $offset = 0, $start_date = '', $end_date = '' ) {
        global $wpdb;

        $table_name = self::get_table_name();

        // Build the WHERE clause based on the time filter.
        $where_time = '';
        switch ( $filter ) {
            case '24h':
                $where_time = $wpdb->prepare(
                    ' AND fb_created_at >= %s',
                    gmdate( 'Y-m-d H:i:s', strtotime( '-24 hours' ) )
                );
                break;
            case '48h':
                $where_time = $wpdb->prepare(
                    ' AND fb_created_at >= %s',
                    gmdate( 'Y-m-d H:i:s', strtotime( '-48 hours' ) )
                );
                break;
            case '7d':
                $where_time = $wpdb->prepare(
                    ' AND fb_created_at >= %s',
                    gmdate( 'Y-m-d H:i:s', strtotime( '-7 days' ) )
                );
                break;
            default:
                // 'all' - no additional time constraint.
                break;
        }

        // Build the WHERE clause based on the site/page filter.
        $where_site = '';
        if ( 'all' !== $site && ! empty( $site ) ) {
            $where_site = $wpdb->prepare(
                ' AND page_name = %s',
                $site
            );
        }

        // Build the WHERE clause based on date range.
        $where_date = '';
        if ( ! empty( $start_date ) ) {
            $where_date .= $wpdb->prepare( ' AND fb_created_at >= %s', $start_date . ' 00:00:00' );
        }
        if ( ! empty( $end_date ) ) {
            $where_date .= $wpdb->prepare( ' AND fb_created_at <= %s', $end_date . ' 23:59:59' );
        }

        // Build pagination LIMIT and OFFSET.
        $limit_clause = '';
        if ( $limit > 0 ) {
            $limit_clause = $wpdb->prepare( ' LIMIT %d OFFSET %d', absint( $limit ), absint( $offset ) );
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is safe.
        $results = $wpdb->get_results(
            "SELECT * FROM $table_name WHERE status = 'pending'{$where_time}{$where_site}{$where_date} ORDER BY fb_created_at DESC{$limit_clause}"
        );

        return $results ? $results : array();
    }

    /**
     * Get the count of pending posts matching filters.
     *
     * @param string $filter     Time filter key: '24h', '48h', '7d', or 'all'.
     * @param string $site       Site/Page name to filter by, or 'all'.
     * @param string $start_date Start date (YYYY-MM-DD format).
     * @param string $end_date   End date (YYYY-MM-DD format).
     * @return int Total count.
     */
    public static function get_pending_posts_count( $filter = 'all', $site = 'all', $start_date = '', $end_date = '' ) {
        global $wpdb;

        $table_name = self::get_table_name();

        $where_time = '';
        switch ( $filter ) {
            case '24h':
                $where_time = $wpdb->prepare(
                    ' AND fb_created_at >= %s',
                    gmdate( 'Y-m-d H:i:s', strtotime( '-24 hours' ) )
                );
                break;
            case '48h':
                $where_time = $wpdb->prepare(
                    ' AND fb_created_at >= %s',
                    gmdate( 'Y-m-d H:i:s', strtotime( '-48 hours' ) )
                );
                break;
            case '7d':
                $where_time = $wpdb->prepare(
                    ' AND fb_created_at >= %s',
                    gmdate( 'Y-m-d H:i:s', strtotime( '-7 days' ) )
                );
                break;
        }

        $where_site = '';
        if ( 'all' !== $site && ! empty( $site ) ) {
            $where_site = $wpdb->prepare( ' AND page_name = %s', $site );
        }

        $where_date = '';
        if ( ! empty( $start_date ) ) {
            $where_date .= $wpdb->prepare( ' AND fb_created_at >= %s', $start_date . ' 00:00:00' );
        }
        if ( ! empty( $end_date ) ) {
            $where_date .= $wpdb->prepare( ' AND fb_created_at <= %s', $end_date . ' 23:59:59' );
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is safe.
        return (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM $table_name WHERE status = 'pending'{$where_time}{$where_site}{$where_date}"
        );
    }

    /**
     * Get a list of unique page/site names present in the database.
     *
     * @return array Array of unique page names.
     */
    public static function get_unique_sites() {
        global $wpdb;
        $table_name = self::get_table_name();
        $results = $wpdb->get_col( "SELECT DISTINCT page_name FROM $table_name ORDER BY page_name ASC" );
        return $results ? $results : array();
    }

    /**
     * Update the status of a specific post record.
     *
     * @param int    $id     The row ID.
     * @param string $status New status value: 'pending', 'processed', or 'ignored'.
     * @return bool True on success, false on failure.
     */
    public static function update_status( $id, $status ) {
        global $wpdb;

        $table_name     = self::get_table_name();
        $allowed_status = array( 'pending', 'processed', 'ignored' );

        if ( ! in_array( $status, $allowed_status, true ) ) {
            return false;
        }

        $result = $wpdb->update(
            $table_name,
            array( 'status' => $status ),
            array( 'id' => absint( $id ) ),
            array( '%s' ),
            array( '%d' )
        );

        return false !== $result;
    }

    /**
     * Get a single post record by its row ID.
     *
     * @param int $id The row ID.
     * @return object|null The row object or null.
     */
    public static function get_post_by_id( $id ) {
        global $wpdb;

        $table_name = self::get_table_name();

        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                absint( $id )
            )
        );
    }

    public static function delete_post( $id ) {
        global $wpdb;

        $table_name = self::get_table_name();

        $result = $wpdb->delete(
            $table_name,
            array( 'id' => absint( $id ) ),
            array( '%d' )
        );

        return false !== $result;
    }

    /**
     * Delete all pending post records from the database.
     *
     * @return int|false Number of deleted rows on success, false on failure.
     */
    public static function delete_all_pending() {
        global $wpdb;

        $table_name = self::get_table_name();

        $result = $wpdb->query(
            "DELETE FROM $table_name WHERE status = 'pending'"
        );

        return $result;
    }
}
