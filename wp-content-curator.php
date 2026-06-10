<?php
/**
 * Plugin Name: WP Content Curator
 * Plugin URI:  https://github.com/your-repo/wp-content-curator
 * Description: A content curation panel that fetches Facebook Page posts, allows AI-powered rewriting, and publishes them as native WordPress entries.
 * Version:     1.2.0
 * Author:      Roberto Suárez
 * Author URI:  https://your-site.com
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-content-curator
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Plugin constants.
 */
define( 'WP_CONTENT_CURATOR_VERSION', '1.2.0' );
define( 'WP_CONTENT_CURATOR_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_CONTENT_CURATOR_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_CONTENT_CURATOR_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Load required class files.
 */
require_once WP_CONTENT_CURATOR_DIR . 'includes/class-content-curator-db.php';
require_once WP_CONTENT_CURATOR_DIR . 'includes/class-content-curator-api.php';
require_once WP_CONTENT_CURATOR_DIR . 'includes/class-content-curator-cron.php';
require_once WP_CONTENT_CURATOR_DIR . 'includes/class-content-curator-admin.php';

/**
 * Plugin activation callback.
 *
 * Creates the custom database table and schedules the cron event.
 */
function content_curator_activate() {
    content_curator_DB::create_table();
    content_curator_Cron::activate();
}
register_activation_hook( __FILE__, 'content_curator_activate' );

/**
 * Plugin deactivation callback.
 *
 * Clears scheduled cron events to prevent residual resource usage.
 */
function content_curator_deactivate() {
    content_curator_Cron::deactivate();
}
register_deactivation_hook( __FILE__, 'content_curator_deactivate' );

/**
 * Initialize the plugin on plugins_loaded.
 */
function content_curator_init() {
    // Register the custom cron interval.
    content_curator_Cron::init();

    // Initialize the admin panel (menus, AJAX handlers, asset enqueueing).
    $admin = new content_curator_Admin();
    $admin->init();
}
add_action( 'plugins_loaded', 'content_curator_init' );
