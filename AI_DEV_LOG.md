# AI Development Log - WP Content Curator

## 2026-06-17 - 18:55 - Solved History Table Responsiveness, styled CSV Export Button, and Bumped to 1.6.7

### Summary of Changes
- **assets/css/admin-style.css**:
  - Added a `min-width: 1500px !important;` to `.cc-history-table` to prevent the WordPress `.wp-list-table` container width constraints from squeezing columns, resolving the issue where the actions column (and thus the delete button) was hidden/cut-off on the right.
  - Styled `.cc-export-btn` and its hover state using `var(--cc-primary)` and `var(--cc-primary-hover)` (blue theme), improving text contrast and readability.
- **wp-content-curator.php**:
  - Bumped version to `1.6.7`.
- **README.md**:
  - Added changelog entry for version `1.6.7`.

## 2026-06-17 - 18:35 - Added Permanent Deletion from History and Bumped to 1.6.6

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Registered translation strings in English, Spanish, and French for the new history delete action and confirmation dialog notices (`history_delete`, `history_delete_confirm`, `history_delete_success`, and `history_delete_error`).
  - Added new AJAX handler `wp_ajax_content_curator_history_delete` and the corresponding `ajax_history_delete()` method that calls `Content_Curator_DB::delete_post()` to permanently delete a post by ID.
  - Localized the new history delete string variables to be passed to javascript.
  - Appended the Delete button markup inside the Actions column (`col-actions`) in `render_history_page()`.
- **assets/js/admin-script.js**:
  - Implemented the jQuery AJAX click event handler for `.cc-history-delete-btn` which shows a confirmation popup to prevent accidental deletion, performs the AJAX request, and scales/removes the deleted row with animation.
- **assets/css/admin-style.css**:
  - Added CSS rule definitions for `.cc-history-delete-btn` and its hover state (red border, red hover highlight) matching the general dashboard style guide.
- **wp-content-curator.php**:
  - Bumped version to `1.6.6`.
- **README.md**:
  - Added changelog entry for version `1.6.6`.

## 2026-06-17 - 18:15 - Detailed Publication Status, WordPress edit/view links and Bumped to 1.6.5

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Updated `get_history_post_mapped_data()` to query and resolve specific publication statuses (Publish, Draft, Future/Scheduled, Ignored, or Processed fallback) and fetch the corresponding view/edit URLs in WordPress.
  - Modified `render_history_page()` to render dynamic, specific status labels and CSS badge classes (`cc-badge-publish`, `cc-badge-draft`, `cc-badge-future`, etc.) and append the WP edit/view link in the URL column.
  - Modified `ajax_export_history()` to export the new dedicated 'URL WordPress' column right after 'URL Original', and the detailed 'Estado' status text at the end of each row.
- **assets/css/admin-style.css**:
  - Added CSS rule properties for the new badges (`.cc-badge-publish`, `.cc-badge-draft`, `.cc-badge-future`) and `.cc-url-separator` vertical pipe divider.
- **wp-content-curator.php**:
  - Bumped version to `1.6.5`.
- **README.md**:
  - Added changelog entry for version `1.6.5`.

## 2026-06-17 - 18:05 - Integrated Dynamic show/hide for AI Settings fields and Bumped to 1.6.4

### Summary of Changes
- **includes/class-content-curator-admin.php**: Added custom class attributes (`cc-ai-api-key-row`, `cc-ai-model-row cc-ai-model-openai`, etc.) to settings fields (`add_settings_field` args parameter) to allow selective show/hide querying.
- **assets/js/admin-script.js**: Implemented `toggleAIFields()` show/hide listener function. Changes are triggered dynamically on AI Provider select input `change` events and on DOM initialization.
- **wp-content-curator.php**: Bumped plugin version and constants to `1.6.4`.
- **README.md**: Added `1.6.4` entry in the Changelog section detailing the settings field dynamic toggle changes.

## 2026-06-17 - 18:00 - Stripped Emojis from CSV Export, Linked History Mapped Columns and Bumped to 1.6.3

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Implemented `get_history_post_mapped_data()` to query the linked WordPress post for a given database record and map 11 specific columns: ID, URL Original, Titular (Title), Contenido (Content), Tipo de evento (Post Type), Etiquetas (Tags), Fecha Publicación Original (Original Facebook Created At), Fecha Publicación (WordPress Publish Date), Lugar (Place), Categorías, and Concellos.
  - Implemented `strip_emojis()` helper method to remove emoji characters and visual symbols from text fields.
  - Updated `ajax_export_history()` to call `get_history_post_mapped_data( $post, true )` to strip emojis and format the export data with the 11 detailed columns.
  - Updated `render_history_page()` to render the history list table UI with these 11 detailed columns, retrieving fields from the linked WordPress posts (with fallbacks if no linked post is found).
  - Stored `_fb_post_id` in WordPress post meta when manually publishing or saving drafts.
- **includes/class-content-curator-cron.php**: Stored `_fb_post_id` in WordPress post meta when automatically curating posts in CRON tasks.
- **assets/css/admin-style.css**: Styled the new table columns and updated `.cc-history-table-wrap` to support horizontal scrolling (`overflow-x: auto;`).
- **wp-content-curator.php**: Bumped plugin version and constants to `1.6.3`.
- **README.md**: Added `1.6.3` entry in the Changelog section detailing the export emojis stripping and new history columns.

## 2026-06-17 - 17:19 - Changed Delete Curation Action to Ignore and Bumped to 1.6.2

### Summary of Changes
- **includes/class-content-curator-db.php**: Added static method `ignore_all_pending()` to update status of all pending post records to `'ignored'` instead of deleting them.
- **includes/class-content-curator-admin.php**:
  - Updated `ajax_delete()` to update post status to `'ignored'` using `Content_Curator_DB::update_status()` instead of deleting the row.
  - Updated `ajax_delete_all()` to update all pending posts to `'ignored'` using `Content_Curator_DB::ignore_all_pending()` instead of deleting the rows.
  - Updated English, Spanish, and French dictionaries (`confirm_delete`, `confirm_delete_all`, `deleting`, `deleting_all`, `success_delete`, and `success_delete_all`) to refer to ignoring/discarding posts and moving them to the History instead of deleting them.
- **wp-content-curator.php**: Bumped plugin version and constants to `1.6.2`.
- **README.md**: Added `1.6.2` entry in the Changelog section detailing the ignore changes.

## 2026-06-17 - 16:50 - Bumped version to 1.6.1

### Summary of Changes
- **wp-content-curator.php**: Bumped plugin version and constants to `1.6.1`.
- **README.md**: Added `1.6.1` entry in the Changelog section detailing the live filter fix for CSV exports.

## 2026-06-17 - 16:31 - Export CSV now respects live filter selection

### Summary of Changes
- **assets/js/admin-script.js**: Updated the `#cc-history-export-btn` click handler to read the current filter values directly from the live DOM inputs (`#cc-history-status-filter`, `#cc-history-site-filter`, `#cc-history-start-date`, `#cc-history-end-date`) instead of static `data-*` attributes on the button (which were fixed at page-load time and did not reflect filter changes made client-side).
- **includes/class-content-curator-admin.php**: Removed the now-unused `data-status`, `data-site`, `data-start`, and `data-end` attributes from the export button markup.


### Summary of Changes
- **includes/class-content-curator-admin.php** (`ajax_export_history`):
  - Replaced `fputcsv()` (which uses comma as separator and doesn't sanitize newlines) with a manual CSV builder.
  - Changed field separator from `,` to `;` — standard for Excel in Spanish/European locale.
  - Added a `$clean()` helper that strips HTML tags and collapses internal `\r`, `\n`, `\t` characters to a single space, preventing each Facebook post text from spawning multiple phantom rows in Excel.
  - Fields containing the separator or double-quotes are properly escaped by wrapping in quotes and doubling internal quote characters.
  - Added `Cache-Control: must-revalidate` header for better download reliability.


### Summary of Changes
- **includes/class-content-curator-admin.php**: Introduced a new `.card-image-gallery-wrap` outer `<div>` to group the image container, the gallery navigation bar, and the image toggles. Moved `gallery-controls` out of `.card-image-gallery-container` (where it was `position: absolute` inside an `overflow: hidden` element) to a sibling position in normal document flow, immediately below the image container. Similarly moved `.image-toggles` inside the wrap, after the controls.
- **assets/css/admin-style.css**:
  - Added `.card-image-gallery-wrap` as margin-bottom container.
  - Changed `.card-image-gallery-container` top border-radius only (bottom corners now rounded by the bars below it), removed `margin-bottom`.
  - Converted `.gallery-controls` from `position: absolute; bottom: 0` to a normal-flow flex bar with bottom rounded corners, attached visually to the image.
  - Added `.image-toggles` CSS class (previously inline styles): flex row, wraps on small screens, no top border (connected to the controls bar above it), bottom rounded corners.
- **assets/js/admin-script.js**: Updated `changeGalleryImage()` to traverse to `.card-image-gallery-wrap` instead of `.card-image-gallery-container`, since the navigation buttons and gallery counter are now siblings of the image container rather than children of it.


### Summary of Changes
- **includes/class-content-curator-db.php**:
  - Added `build_history_where()` private helper to build reusable SQL WHERE clauses for history queries (status, site, date range filters).
  - Added `get_history_posts()` to retrieve paginated `processed`/`ignored` posts from the custom table.
  - Added `get_history_posts_count()` to count history posts matching active filters (used for pagination).
  - Added `get_history_posts_for_export()` to retrieve all matching history posts without limit (for CSV export).
- **includes/class-content-curator-admin.php**:
  - Extended `get_dictionary()` in all three languages (EN, ES, FR) with 27 new keys covering History page strings: section title, column headers, status labels, export button, modal strings, and requeue confirmation/feedback.
  - Registered new AJAX action hooks: `content_curator_export_history` and `content_curator_history_update_status`.
  - Added History submenu page (`content-curator-history`) with `edit_posts` capability in `register_menus()`.
  - Added history page to the `$plugin_pages` array in `enqueue_assets()` so CSS/JS are loaded correctly.
  - Exposed history JS strings (`history_requeue_confirm`, `history_requeue_success`, `history_requeue_error`, `exporting`, `modal_close`) via `wp_localize_script()`.
  - Implemented `render_history_page()` method: renders premium banner, actions bar with CSV export button and record count badge, filterable form (status, site, date range, clear filters), paginated history table with status badges, text preview cells with expand button, re-queue action per row, and a full-text preview modal overlay.
  - Implemented `ajax_export_history()`: validates nonce/capability, queries all matching history posts, outputs UTF-8 BOM CSV with 7 columns (ID, FB Post ID, Facebook Page, Status, Original Text, FB Created At, Fetched At), and exits directly.
  - Implemented `ajax_history_update_status()`: validates nonce/capability, accepts `post_id` and `new_status`, updates the row in the DB, and returns JSON success/error.
- **assets/css/admin-style.css**:
  - Added `.cc-history-table-wrap`, `.cc-history-table` table layout with styled column widths, header, row hover effect.
  - Added `.cc-status-badge`, `.cc-badge-processed` (green), `.cc-badge-ignored` (orange) pill badges.
  - Added `.cc-history-preview-cell`, `.cc-preview-short`, `.cc-preview-expand-btn` for truncated text preview cells.
  - Added `.cc-history-requeue-btn` with hover effect for re-queue action button.
  - Added `.cc-export-btn` with Excel-green gradient, hover lift effect, and disabled state.
  - Added `.cc-modal-overlay`, `.cc-modal`, `.cc-modal-header`, `.cc-modal-close`, `.cc-modal-body`, `#cc-modal-text-content` with glassmorphic backdrop, slide-up animation, and scrollable body.
- **assets/js/admin-script.js**:
  - Added `openHistoryModal()` / `closeHistoryModal()` helpers.
  - Added click event listener for `.cc-preview-expand-btn` to open the full-text modal.
  - Added click/Escape handlers to close the modal.
  - Added click handler for `.cc-history-requeue-btn`: sends AJAX to `content_curator_history_update_status` with `new_status=pending`, animates row removal on success.
  - Added click handler for `#cc-history-export-btn`: creates a temporary hidden form and submits it as POST to the AJAX endpoint to trigger a native browser CSV download.


## 2026-06-08 - 17:53 - Initial plugin scaffold and full implementation

### Summary of Changes
- Created the complete plugin structure with 8 files across 4 directories.
- **wp-content-curator.php**: Main bootstrap file with plugin headers, constants, activation/deactivation hooks, and class autoloading.
- **includes/class-content-curator-db.php**: Database layer using `dbDelta()` for table creation (`{prefix}_Content_Curator_posts`) with CRUD methods, duplicate prevention via `fb_post_id` UNIQUE key, and parameterized time-filtered queries.
- **includes/class-content-curator-api.php**: API connector encapsulating Facebook Graph API v20.0 post fetching (`wp_remote_get`) and dual AI provider support (OpenAI gpt-4o-mini + Anthropic claude-3-haiku) for text rewriting (`wp_remote_post`). Shared SEO-focused system prompt.
- **includes/class-content-curator-cron.php**: WP-Cron scheduler with custom 12-hour interval (`cron_schedules` filter), activation/deactivation lifecycle, and fetch callback that iterates configured pages and inserts new posts.
- **includes/class-content-curator-admin.php**: Admin panel with menu registration (Dashboard + Settings subpages), WordPress Settings API integration for API credentials, card-grid dashboard rendering with time filter, 3 AJAX handlers (rewrite, publish, fetch_now), and image sideloading via `media_handle_sideload()` with Facebook URL edge-case handling.
- **assets/css/admin-style.css**: Admin stylesheet with CSS custom properties, responsive card grid (2-col desktop, 1-col mobile), button variants with gradients, loading overlay with spinner, notification banners, and empty state.
- **assets/js/admin-script.js**: jQuery AJAX handlers for AI rewriting (textarea highlight feedback), draft/publish with card fade-out removal, manual fetch trigger, notification system, and loading overlay management.
- **README.md**: Technical documentation covering installation, Facebook/AI configuration, usage workflow, WP-Cron reliability, file structure, security measures, and changelog.

## 2026-06-08 - 18:45 - Migrated Facebook Graph API to Apify API

### Summary of Changes
- **includes/class-content-curator-api.php**: Replaced Facebook Graph API client with Apify Facebook Posts Scraper synchronous API (`run-sync-get-dataset-items` endpoint). Added normalizers for page inputs and mapped the scraper's dataset fields to fit the plugin's DB layer.
- **includes/class-content-curator-admin.php**: Modified Settings configuration, changing the Facebook Access Token input field to request the Apify API Token (`Content_Curator_apify_token`), and updated page IDs field guidelines to accept Page URLs and usernames.
- **includes/class-content-curator-cron.php**: Updated background cron fetch job to fetch and pass the Apify API token and support the new URL formatting.
- **README.md**: Updated documentation and changelog to reflect Apify integration.

## 2026-06-08 - 18:47 - Added Custom Branding Assets and Updated Author

### Summary of Changes
- **wp-content-curator.php**: Updated the plugin's header to set `Author` as "Roberto Suárez" and bumped the version constant to `1.1.0`.
- **assets/images/icon.png**: Generated and saved a custom plugin vector-style icon.
- **assets/images/banner.png**: Generated and saved a sleek custom neon gradient header banner.
- **includes/class-content-curator-admin.php**: Set the custom icon image as the sidebar menu icon, updated the dashboard page structure to include the premium branding header, and added an `admin_head` hook (`admin_menu_icon_css`) to constrain the menu icon to 20x20px to prevent visual overflow on other WordPress admin pages.
- **assets/css/admin-style.css**: Styled the premium header banner overlay, custom icon wrapper, and fonts for high-end aesthetic presentation.
- **README.md**: Expanded detailed instructions on how to configure Apify, find the API token, and map public page URLs.

## 2026-06-08 - 18:52 - Reverted Sidebar Menu Icon to Dashicons and Bumped Version

### Summary of Changes
- **includes/class-content-curator-admin.php**: Reverted the admin menu icon back to `'dashicons-facebook'` and removed the `admin_head` hook to clean up the custom styles, restoring the standard WordPress admin sidebar layout.
- **wp-content-curator.php**: Bumped plugin version to `1.1.1` to force WordPress to clear caching/transients and recognize the updated code.
- **README.md**: Updated changelog with version `1.1.1`.

## 2026-06-08 - 19:11 - Renamed Project to WP Content Curator

### Summary of Changes
- **wp-content-curator.php**: Changed the Plugin Name header to "WP Content Curator".
- **includes/class-content-curator-admin.php**: Renamed menu and settings submenu labels to "Content Curator" and "Content Curator Settings".
- **README.md**: Updated main title to "WP Content Curator".
- **AI_DEV_LOG.md**: Updated title and logged project renaming.

## 2026-06-08 - 19:13 - Total Refactor & File Renaming to WP Content Curator

### Summary of Changes
- Renamed all class files under `includes/` to follow the prefix `class-content-curator-*.php`.
- Renamed plugin folder to `wp-content-curator` and main bootstrap entry file to `wp-content-curator.php`.
- Refactored all PHP classes, constants, variables, database tables, option keys, script/style handles, nonces, and CSS/JS variables from "facebook-curator" / "fb_curator" to "content-curator" / "content_curator" to achieve complete naming consistency.

## 2026-06-08 - 19:37 - Lowercased Option Keys and Hooks and Bumped to 1.1.2

### Summary of Changes
- **includes/class-content-curator-admin.php** & **assets/js/admin-script.js**: Replaced capitalized `Content_Curator_` database option keys, nonces, and AJAX hooks with lowercase `content_curator_` to resolve option-loading and manual-fetch AJAX failures.
- **wp-content-curator.php**: Bumped version to `1.1.2`.
- **README.md**: Updated changelog.

## 2026-06-08 - 19:46 - Added Site Filtering and Modified Button Colors in 1.1.3

### Summary of Changes
- **includes/class-content-curator-db.php**: Added page/site filtering support to `get_pending_posts` and added a helper `get_unique_sites` to retrieve all unique site names.
- **includes/class-content-curator-admin.php**: Integrated a "Filter by Site" dropdown selector in the dashboard toolbar alongside the hours filter, preserving selected parameters on change.
- **assets/css/admin-style.css**: Replaced white button text colors with black (`#000000`) for high-contrast readability against gradient/success/warning button backgrounds.
- **wp-content-curator.php**: Bumped version to `1.1.3`.
- **README.md**: Updated changelog.

## 2026-06-08 - 19:49 - Expanded Media URL Extractors in 1.1.4

### Summary of Changes
- **includes/class-content-curator-api.php**: Expanded the fallback loop for `image_url` extraction to look for `thumbnail`, `media` (string or `url` inside array item), and nested `attachments[0]->media->image->src` keys to handle all output formats of Apify Facebook Page scrapers.
- **wp-content-curator.php**: Bumped version to `1.1.4`.
- **README.md**: Updated changelog.

## 2026-06-08 - 19:56 - Robust Image Extraction, Pagination, Date Filters, and Dashboard Fetch Button in 1.1.5

### Summary of Changes
- **includes/class-content-curator-api.php**: Implemented a highly robust `extract_image_url()` helper parsing flat fields, nested arrays/objects inside `media`, direct `images`/`image_urls` lists, and nested `attachments` metadata.
- **includes/class-content-curator-db.php**: Updated `get_pending_posts()` to support pagination parameters (`limit`, `offset`) and date filtering (`start_date`, `end_date`). Added `get_pending_posts_count()` count helper.
- **includes/class-content-curator-admin.php**: Enhanced dashboard toolbar with start/end date filters, added "Fetch Now" button to curation dashboard with status loading, and implemented grid pagination.
- **assets/js/admin-script.js**: Updated manual fetch handler to automatically refresh the curation dashboard upon success, and resolved charset decoding locks.
- **assets/css/admin-style.css**: Added stylesheet definitions for pagination links and date picker range input elements.
- **wp-content-curator.php**: Bumped plugin version to `1.1.5`.
- **README.md**: Updated changelog.

## 2026-06-08 - 20:04 - Post Deletion Functionality for Curation Panel and Version 1.1.6

### Summary of Changes
- **includes/class-content-curator-db.php**: Added a static method `delete_post($id)` to remove curated post rows by ID from the custom table.
- **includes/class-content-curator-admin.php**: Registered `wp_ajax_content_curator_delete` AJAX hook, implemented `ajax_delete()` handler, enqueued new localized text strings, and rendered the Delete button in the dashboard card actions template.
- **assets/js/admin-script.js**: Implemented click event handler for `.btn-delete` to confirm, run the delete AJAX request, and dynamically fade out and remove cards upon successful deletion.
- **assets/css/admin-style.css**: Styled the `.btn-delete` button with a premium crimson background, hover scale transitions, and matching shadows.
- **wp-content-curator.php**: Bumped version constant and headers to `1.1.6`.
- **README.md**: Documented version `1.1.6` changes in the changelog.

## 2026-06-08 - 20:09 - Fixed Date Mapping and Parsing Bug for Apify Scraper and Version 1.1.7

### Summary of Changes
- **includes/class-content-curator-api.php**: Updated `fetch_page_posts()` mapping to check the Apify `time` field before `date` or `timestamp`.
- **includes/class-content-curator-cron.php**: Updated date parser in `run_fetch()` to handle numeric Unix timestamps (by casting to int) alongside standard ISO 8601 strings (via `strtotime()`).
- **wp-content-curator.php**: Bumped version to `1.1.7` in the plugin headers and constant definition.
- **README.md**: Added changelog entry for version `1.1.7`.
- **debug-apify.php**: Created a temporary debug script to print raw API keys and date values from Apify, and deleted it once validation was complete.

## 2026-06-08 - 20:15 - Added Delete All Pending Toolbar Option and Fixed Button Text Color to Black in 1.1.8

### Summary of Changes
- **includes/class-content-curator-db.php**: Added a static method `delete_all_pending()` to delete all rows with status `'pending'` from the custom database table.
- **includes/class-content-curator-admin.php**: Registered the `wp_ajax_content_curator_delete_all` AJAX hook, implemented `ajax_delete_all()` handler, registered localized strings for confirmation and status notices, and rendered the "Delete All Pending" button in the toolbar.
- **assets/js/admin-script.js**: Added click event listener for `#content-curator-delete-all` to confirm, run the delete all AJAX call, display status alerts, and reload the page on success to trigger the empty dashboard state.
- **assets/css/admin-style.css**: Updated the text color of `.btn-delete` and `.btn-delete-all` buttons (and their hover/focus states) to `#000000` (black) for better readability, and added stylesheet definitions for `.btn-delete-all`.
- **wp-content-curator.php**: Bumped version to `1.1.8`.
- **README.md**: Documented version `1.1.8` changes in the changelog.

## 2026-06-08 - 20:22 - Added Image URL Filtering Validation in 1.1.9

### Summary of Changes
- **includes/class-content-curator-api.php**: Implemented `is_valid_image_url()` validation helper to filter out Facebook post/story/video HTML links (e.g. `facebook.com` hosts) and only accept raw CDN resources (`fbcdn.net` hosts) or common image file extensions. Updated `extract_image_url()` to run candidates through this helper.
- **wp-content-curator.php**: Bumped version to `1.1.9`.
- **README.md**: Added changelog entry for version `1.1.9`.

## 2026-06-08 - 20:36 - Added Multi-Image Posts and WordPress 7 Native AI in 1.2.0

### Summary of Changes
- **includes/class-content-curator-api.php**: Added `extract_image_urls()` to grab all valid unique image URLs in a scraper item. Changed `fetch_page_posts()` mapping to JSON-encode and save the images list in the database. Added `rewrite_with_wordpress_ai()` method using WordPress 7.0 core `wp_ai_client_prompt()` client, and bypassed key check in `rewrite_text()` for this provider.
- **includes/class-content-curator-db.php**: Modified `insert_post()` to identify JSON arrays in `image_url` and sanitize each item before database query insertion.
- **includes/class-content-curator-admin.php**: Rendered a premium slideshow/gallery container with slide-show navigation and counters in `render_dashboard_page()`. Registered the "WordPress 7 Native AI" option dynamically in the Settings dropdown field. Modified `ajax_publish()` to sideload all attached images, set the first image as Featured Image, and embed a block Gutenberg gallery containing all post images.
- **assets/js/admin-script.js**: Added global `changeGalleryImage` method to handle gallery slider toggles.
- **assets/css/admin-style.css**: Styled the slideshow gallery, control bar, arrows, and counters.
- **wp-content-curator.php**: Bumped version to `1.2.0`.
- **README.md**: Documented version `1.2.0` additions.

## 2026-06-08 - 20:51 - Añadido soporte nativo para la API de Google Gemini en WP Post Curator

### Summary of Changes
- Added native support for Google Gemini API (`gemini-1.5-flash`) inside settings to enable free text rewrites.

## 2026-06-08 - 20:53 - Añadido campo 'System Prompt' en los ajustes de WP Post Curator

### Summary of Changes
- Added 'System Prompt' configuration field in settings to easily customize rewriting instructions sent to the AI.

## 2026-06-10 - 00:33 - Created AGENTS.md context reference file

### Summary of Changes
- Created `AGENTS.md` context reference containing project summaries, active plugin lists, and development guidelines for AI assistants.

## 2026-06-10 - 00:46 - Added UI translation dictionary, dynamic custom tag assignment, and WPML post translation mapping

### Summary of Changes
- **includes/class-content-curator-admin.php**: Implemented `get_dictionary( $lang )` containing full UI translations for English, Spanish, and French. Registered and rendered the `'content_curator_plugin_language'` settings field. Implemented `ajax_change_plugin_lang()` AJAX handler for dynamic toolbar language switching. Queried and rendered Post Type, WPML Language, and Custom Taxonomy Tag selectors inside curation dashboard card forms. Updated `ajax_publish()` to process post type, set taxonomy tag relationships using `wp_set_object_terms()`, and register post languages in WPML using `wpml_set_element_language_details` action hook.
- **assets/js/admin-script.js**: Gathered post type, language, and tag fields inside publish click handler and passed them to the backend AJAX request. Added dynamic listener to `#content-curator-plugin-lang-select` to trigger plugin UI language changes.
- **assets/css/admin-style.css**: Styled the grid meta selectors inside post cards and the language switcher dropdown in the curation toolbar.
- **AGENTS.md**: Documented the CPT, custom taxonomy, WPML translation, and UI translation capabilities.

## [2026-06-10] - [01:10] - [Implemented WPML post copying across all active languages and a configurable external CRON endpoint with automatic AI translation]

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Added `'all_languages'` and CRON settings translations to `get_dictionary()`.
  - Refactored `sideload_image()` to be `public static`.
  - Registered options for external CRON (`content_curator_enable_external_cron`, `content_curator_external_cron_token`, `content_curator_external_cron_limit`, `content_curator_external_cron_status`).
  - Rendered the External CRON Configuration section and fields (including a copyable trigger URL).
  - Updated card language selector in `render_dashboard_page()` to dynamically list WPML languages with "All Languages" prepended, or hide if inactive.
  - Updated `ajax_publish()` to process `'all'` by inserting a default language post and cloning/linking translations for all other active languages.
- **includes/class-content-curator-api.php**:
  - Updated `rewrite_text()` and provider methods to accept `$target_lang` parameter.
  - Modified `get_system_prompt()` to append translation guidelines if `$target_lang` is set.
- **includes/class-content-curator-cron.php**:
  - Hooked `maybe_trigger_external_cron()` on WordPress `init` action.
  - Implemented token authentication, query parameter overrides (`limit`, `status`), and JSON status output.
  - Implemented `process_pending_posts_automatically()` to automatically process pending posts, translate content for each active language using AI, insert posts, and link them.
- **AGENTS.md**:
  - Updated documentation to reflect WPML cloning and external CRON capabilities.

## [2026-06-10] - [01:25] - [Implemented Multi-Language Tabbed Curation Editor, Curated Languages checkbox settings, and default post type/tag settings]

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Registered settings options for curated languages checklist, default post type, and default tag.
  - Rendered settings fields: checkboxes for curated languages, CPT dropdown for default post type, and non-hierarchical taxonomy dropdown for default tag.
  - Modified Curation Dashboard card rendering to show tabs for each selected curated language and corresponding textareas, pre-selecting the default post type and tag, and removing the single post language select.
  - Refactored `ajax_rewrite()` to run AI optimization/translations concurrently for all curated languages in a loop.
  - Refactored `ajax_publish()` to decode JSON-encoded `texts` dictionary, insert the default language master post, sideload images, create individual translations for each curated language tab's content, and link them programmatically in WPML.
- **assets/js/admin-script.js**:
  - Added event listener for editor tab button clicks to switch between language tab textareas.
  - Updated AI rewrite button (`.btn-ai`) handler to send the active tab text and populate all language textareas with their translations returned from the backend.
  - Updated Draft (`.btn-draft`) and Publish (`.btn-publish`) handlers to serialize all language textareas into a JSON string `texts` payload sent to the server.
- **assets/css/admin-style.css**:
  - Added styling for the tabbed editor bar container (`.editor-tabs`), tab buttons (`.editor-tab-btn`), hover states, and active tab indicators.
  - Adjusted curation card meta selector grid (`.card-meta-selects`) to use 2 columns instead of 3.
- **AGENTS.md**:
  - Updated agent context documentation to reflect tabbed editor and default settings options.

## 2026-06-10 - 19:20 - Implemented CPT agenda event fields and custom taxonomies integration

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Added event fields UI dictionary translations in English, Spanish, and French.
  - Queried terms for `categorias-agenda` and `concellos-eventos` taxonomies.
  - Rendered `agenda-only-fields` form block containing inputs for event start/end dates, location, coordinates, video URL, and multi-select elements for the custom taxonomies.
  - Updated `ajax_publish()` to parse the event POST request params, write metadata (converting dates to UNIX timestamps, formatting gallery attachment IDs, and saving coordinates, video URL, and location), and associate terms for `categorias-agenda` and `concellos-eventos` taxonomies on master and translated posts.
- **assets/js/admin-script.js**:
  - Added a change listener to `.select-post-type` to slideDown the `.agenda-only-fields` block when `agenda` is selected, and slideUp for other post types. Triggered on document ready.
  - Updated draft/publish submit handler to read dates, location, coordinates, video URL, and multi-selected taxonomy term IDs, and send them in the AJAX request payload.
- **assets/css/admin-style.css**:
  - Enqueued aesthetic visual styling for the `.agenda-only-fields` card section with a light primary tint background, dashed borders, modern input shadows, and responsive media query columns.

## 2026-06-10 - 20:05 - Created gitignore and completed initial repository push

### Summary of Changes
- **.gitignore**: Created a `.gitignore` file in the plugin root to ignore OS metadata and IDE configuration files.
- **Git Repository**: Added all existing plugin files and performed the initial commit and push to the remote GitHub repository.

## 2026-06-10 - 20:07 - Documented external dependencies in AGENTS.md

- **AGENTS.md**: Added a new section "External Dependencies & Integrations" listing external API / AI providers (Apify, OpenAI, Anthropic, Gemini, WordPress 7 Native AI) and third-party WordPress plugin integrations (WPML, Crocoblock/JetEngine custom fields and taxonomies).

## 2026-06-10 - 20:10 - Relocated Optimize with AI button below text editor

### Summary of Changes
- **includes/class-content-curator-admin.php**: Relocated the "Optimize with AI" (`btn-ai`) button from the card bottom actions container (`card-actions`) to right underneath the tabbed editor textareas (`card-editor-actions` within `card-editor`).
- **assets/css/admin-style.css**: Extended the custom premium button styling selectors to target `.card-editor-actions .button` and its nested dashicons to match the existing look.
- **assets/js/admin-script.js**: Updated the card loading functions (`showCardLoading`, `hideCardLoading`) to select and disable/enable the newly relocated AI button.

## 2026-06-10 - 20:14 - Bumped plugin version to 1.2.1 and updated changelog

### Summary of Changes
- **wp-content-curator.php**: Bumped version to `1.2.1` in the plugin headers and constants.
- **README.md**: Updated changelog to document version `1.2.1`.

## 2026-06-10 - 20:17 - Restructured settings page into tabbed layout

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Restructured the settings page layout into a tabbed menu using standard WordPress `.nav-tab-wrapper` tabs.
  - Divided settings fields into three separate tabs matching basic, AI, and CRON sections respectively.
  - Updated translation dictionary (`get_dictionary`) with custom translations for basic, AI, and CRON tab titles in English, Spanish, and French.
- **assets/js/admin-script.js**:
  - Added click event handler for settings tabs to toggle active class and display corresponding settings section.
  - Implemented automatic redirection hash recovery by updating the hidden `_wp_http_referer` form field before submission to stay on the active tab post-save.
  - Added on-load hash check to automatically activate the tab matching the URL hash.

## 2026-06-10 - 20:18 - Switched AI rewrite errors to browser alert popups

- **assets/js/admin-script.js**: Replaced standard top-of-page notification banners (`showNotice`) with browser-level modal alert dialogs (`alert()`) for any errors occurring during the "Optimize with AI" process.

## 2026-06-10 - 20:20 - Implemented custom success modal with post link on publish/draft save

### Summary of Changes
- **includes/class-content-curator-admin.php**: Modified the `ajax_publish()` success response payload to return the post's permalink (`post_url`).
- **assets/js/admin-script.js**:
  - Implemented `showPublishSuccessModal(message, url, isDraft)` to render a premium glassmorphic modal with a checkmark icon, success message, and a button link pointing to the newly created post (view URL for published posts, edit URL for draft posts).
  - Wired the modal popup trigger inside the AJAX success callback of the publish/draft button handler.
  - Replaced publish/draft saving error banners with browser alert popups.

## 2026-06-11 - 00:18 - Fixed PHP syntax parse error in settings page method definition

### Summary of Changes
- **includes/class-content-curator-admin.php**: Fixed syntax parse error on settings page render method where a trailing comment block commented out the function definition.
- **wp-content-curator.php**: Bumped plugin version to `1.2.2`.
- **README.md**: Updated changelog.

## 2026-06-11 - 00:26 - Fixed 'Lugar' event field metadata saving key casing

### Summary of Changes
- **includes/class-content-curator-admin.php**: Updated the `'Lugar'` metadata key inside the CPT agenda publication block to `'lugar'` (lowercase) in both master post and translated post update sections, matching the actual JetEngine field key identifier.
- **wp-content-curator.php**: Bumped plugin version to `1.2.3`.
- **README.md**: Updated changelog.
- **AGENTS.md**: Corrected occurrences of `'Lugar'` to `'lugar'` in meta fields specification.

## 2026-06-11 - 00:30 - Added CPT Agenda Default Values configuration and pre-filling

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Registered `content_curator_agenda_defaults` array option and added its section and field.
  - Implemented `sanitize_agenda_defaults($value)` to clean inputs from the dynamic defaults table.
  - Implemented `render_field_agenda_defaults()` to draw the dynamic configuration table.
  - Added a 4th tab in settings layout to hold the Agenda Defaults table.
  - Queried matching default values inside the curation card grid loop, pre-filling start/end times (concatenated with the post's publish date), location, and coordinates if configured.
  - Added new UI dictionary translations in English, Spanish, and French.
- **assets/js/admin-script.js**: Added jQuery handlers to add and remove default agenda rows, re-indexing inputs to ensure sequential submission array indexes.
- **wp-content-curator.php**: Bumped plugin version to `1.3.0`.
- **README.md**: Documented version `1.3.0` changes.
- **AGENTS.md**: Documented new Agenda Defaults config capability.

## 2026-06-11 - 00:48 - Reordered settings tabs, updated titles, and styled defaults table

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Reordered and restructured settings tab rendering to fix a broken HTML container nesting and a PHP syntax error.
  - Rendered the event configuration defaults table directly inside the tab container (bypassing the form-table column constraints) to allow full-width sizing and prevent layout squeezing.
  - Removed inline widths from the table inputs.
- **assets/css/admin-style.css**: Added premium custom styling rules for the event defaults configuration table (`#cc-agenda-defaults-table`) to ensure comfortable layout spacing and high-end visual appearance.

## 2026-06-11 - 01:00 - Simplified event fields, changed times to date-only format, added today checkbox option, and bumped version to 1.4.0

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Simplified CPT Agenda event fields on curation cards: removed coordinates and video fields from the card editor layout, leaving only start date, end date, place (lugar), categories, and concellos.
  - Switched event start/end inputs on curation cards and default configurations from `datetime-local`/`time` inputs to standard date-only `date` inputs.
  - Added a checkbox option (`use_today`) to the event default settings table allowing page defaults to automatically fallback to the current day's date.
  - Removed coordinates and video fields saving in the master and translated posts creation routines.
  - Updated translation dictionaries in English, Spanish, and French for the updated settings headers and checkbox options.
- **assets/js/admin-script.js**:
  - Replaced the defaults row creation template, converting start/end time fields to date fields and adding the `use_today` checkbox markup.
  - Added a change handler for the `use_today` checkbox to automatically toggle the disabled property of the row's date inputs.
  - Added a document-ready function to initialize the disabled state of date inputs based on checked options on load.
- **assets/css/admin-style.css**: Included `input[type="date"]` selector in the default configuration table CSS rules to ensure dates have matching styled inputs.
- **wp-content-curator.php**: Bumped plugin version constant and header metadata to `1.4.0`.
- **README.md**: Updated version references and changelog history for the `1.4.0` release.
- **AGENTS.md**: Updated CPT Agenda integration and default values specifications to align with simplified metadata fields.

## 2026-06-12 - 15:45 - Made gallery images optional and implemented automatic cover image renaming

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Added `'include_gallery_label'` translations to the dictionary in English, Spanish, and French.
  - Added checkbox toggle inside card UI below gallery controls to choose whether to include gallery images in the post (checked by default, only visible for multi-image posts).
  - Modified `ajax_publish()` parameter extraction to parse the new `include_gallery` AJAX param.
  - Added filter logic to `ajax_publish()` to drop all gallery images except the first cover image if `include_gallery` is false.
  - Modified `sideload_image()` to accept an optional `$custom_filename` parameter, sanitizing it using `sanitize_title()` to build the filename while keeping the correct extension.
  - Updated calls inside `ajax_publish()` to pass the post `$title` as the custom filename for the cover image (index 0).
- **includes/class-content-curator-cron.php**:
  - Updated both automatic multi-language and single-language cron loops to pass the `$title` parameter when calling `sideload_image` for the cover image.
- **assets/js/admin-script.js**:
  - Extracted the checked state of `.include-gallery-checkbox` inside cards and passed it as `include_gallery` (1 or 0) in the draft/publish AJAX payload.
- **wp-content-curator.php**:
  - Bumped plugin version and constant definitions to `1.4.1`.
- **README.md**:
  - Added version `1.4.1` details to the changelog.
- **AGENTS.md**:
  - Documented optional gallery toggles and cover image renaming behavior.

## 2026-06-15 - 11:04 - Implemented post title customization in curation card editor and version 1.5.0

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Added dictionary translations for `'title_label'`, `'content_label'`, and `'title_empty'` in English, Spanish, and French.
  - Localized `'title_empty'` string for the enqueued JavaScript client under `strings`.
  - Modified card editor markup inside `render_dashboard_page()`, splitting the original text dynamically into a title (first line) and body (rest).
  - Wrapped each language tab's editor fields inside an `.editor-tab-content-wrapper` container.
  - Rendered a custom text input field for the Title alongside the content textarea for each language tab.
- **assets/js/admin-script.js**:
  - Added a `splitTitleAndBody(text)` helper function to divide text blocks into title and body content.
  - Adjusted editor tab switching click handler to support and toggle the new `.editor-tab-content-wrapper` containers.
  - Updated the AI Optimization trigger to combine title and body before sending, and split the returned translations back into their respective Title and Content fields.
  - Updated the draft/publish submission handlers to aggregate the customized title and content body fields, validate that title is populated if content exists, and combine them back with a newline so the backend remains fully compatible.
- **assets/css/admin-style.css**:
  - Appended premium CSS style definitions for the new `.content-curator-title-input` and `.editor-field-group` container elements.
- **wp-content-curator.php**:
  - Bumped plugin version metadata and constant `WP_CONTENT_CURATOR_VERSION` to `1.5.0`.
- **README.md**:
  - Documented version `1.5.0` features in the changelog section.
- **AGENTS.md**:
  - Updated the key capabilities description for the Tabbed Curation Editor to reflect the new post title capability.

## 2026-06-15 - 15:40 - Renamed manual fetch button and added timeframe filter dropdown next to it in version 1.5.1

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Renamed Spanish manual fetch button translation `'fetch_now'` from "Importar ahora" to "Escanear ahora".
  - Added `'all_time'` and `'last_week'` translations for English, Spanish, and French dictionaries.
  - Added `#content-curator-fetch-timeframe` dropdown filter next to the `#content-curator-fetch-now` scan button inside `render_dashboard_page()`.
  - Modified `ajax_fetch_now()` to retrieve the POST parameter `timeframe` (defaulting to `'all'`) and forward it to `content_curator_Cron::run_fetch()`.
- **includes/class-content-curator-cron.php**:
  - Modified `run_fetch()` to accept an optional `$timeframe = 'all'` parameter and pass it down to `content_curator_API::fetch_page_posts()`.
- **includes/class-content-curator-api.php**:
  - Updated `fetch_page_posts()` to accept a `$timeframe = 'all'` parameter.
  - Added conditional logic to map timeframe values (`24h` and `7d`) to the Apify actor `oldestPostDateUnified` parameter values (`24 hours` and `7 days`) to restrict fetched posts timeframe directly on the Apify synchronous query.
- **assets/js/admin-script.js**:
  - Updated `#content-curator-fetch-now` click listener to read the selected option from `#content-curator-fetch-timeframe` and send it inside the AJAX request payload.
- **wp-content-curator.php**:
  - Bumped plugin version metadata and constant `WP_CONTENT_CURATOR_VERSION` to `1.5.1`.
- **README.md**:
  - Documented version `1.5.1` features in the changelog section.

## 2026-06-15 - 15:50 - Added optional cover image and gallery block selectors in version 1.5.2

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Added `'include_cover_label'` translations to the dictionary in English, Spanish, and French.
  - Replaced the single gallery-checkbox template with a modern card-styled checkbox list allowing independent cover and gallery selection.
  - Extracted the `include_cover` value from AJAX $_POST parameters.
  - Refactored post attachment and sideloading flow: if cover is enabled, it is set as Featured Image. If gallery is enabled, it creates the Gutenberg gallery block.
  - Added fallback: if cover is disabled but gallery is enabled and only 1 image exists, that image is embedded in the post content body as a single image block.
  - Ensured WPML translation posts receive matching gallery/image block layouts and featured image thumbnails depending on selection.
- **assets/js/admin-script.js**:
  - Extracted the checked state of `.include-cover-checkbox` and passed it as `include_cover` (1 or 0) in the draft/publish AJAX payload.
- **wp-content-curator.php**:
  - Bumped plugin version and constants to `1.5.2`.
- **README.md**:
  - Documented version `1.5.2` features in the changelog.
- **AGENTS.md**:
  - Documented optional cover selection and fallback behavior in the capabilities summary.

## 2026-06-15 - 15:58 - Added AI connection test button in settings in version 1.5.3

### Summary of Changes
- **includes/class-content-curator-api.php**:
  - Implemented public `test_ai_connection()` method to dispatch a lightweight prompt to the selected provider (OpenAI, Anthropic, Gemini, or WordPress Native AI) with the provided credentials.
- **includes/class-content-curator-admin.php**:
  - Added `'test_ai_btn'`, `'testing_ai'`, `'test_ai_success'`, and `'test_ai_error'` translation keys in English, Spanish, and French dictionaries.
  - Registered `'content_curator_test_ai'` AJAX handler.
  - Implemented `ajax_test_ai()` method to sanitize provider and API key inputs and invoke `content_curator_API::test_ai_connection()`.
  - Enqueued the new translation strings into `wp_localize_script()`.
  - Added the `#content-curator-test-ai` test button and status element in the AI configuration tab layout inside `render_settings_page()`.
- **assets/js/admin-script.js**:
  - Implemented click event listener for `#content-curator-test-ai` to gather currently typed form field values (provider selection and API Key) and perform the connection test AJAX call.
- **wp-content-curator.php**:
  - Bumped plugin version and constant definitions to `1.5.3`.
- **README.md**:
  - Documented version `1.5.3` changes in the changelog.
- **AGENTS.md**:
  - Added the AI connection testing capability description.

## 2026-06-15 - 16:10 - Implemented custom AI models config, progressive scraper queue, scheduling, and bumped version to 1.5.4

### Summary of Changes
- **includes/class-content-curator-api.php**:
  - Replaced hardcoded AI models `'gpt-4o-mini'`, `'claude-3-haiku-20240307'`, and `'gemini-1.5-flash'` with model names retrieved via `get_option()` settings.
- **includes/class-content-curator-admin.php**:
  - Registered translations for progressive page fetch phases (`fetching_pages_list`, `fetching_page_x_of_y`, `fetch_completed_summary`, and `no_pages_configured`) in English, Spanish, and French.
  - Exposed new translations to the JS client in `wp_localize_script()`.
  - Modified `ajax_publish()` to extract and process custom `publish_date` input, mapping it to post publishing and WPML translation copies using local and GMT formats.
- **assets/js/admin-script.js**:
  - Refactored manual scan click handler (`#content-curator-fetch-now`) to query all active monitor page URLs, sequential AJAX scan pages one by one to avoid timeout errors, and update localized progress messages dynamically.
  - Passed `publish_date` parameter to `ajax_publish` AJAX payload.
- **assets/css/admin-style.css**:
  - Updated `.card-meta-selects` to three columns on desktop.
- **wp-content-curator.php**:
  - Bumped version to `1.5.4`.
- **README.md**:
  - Updated changelog with version `1.5.4` description.
- **AGENTS.md**:
  - Added new capabilities: Progressive Scraper and Future Scheduling, and noted configurable AI models.

## 2026-06-15 - 16:30 - Segregated dashboard actions and filter controls in version 1.5.5

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Segregated the layout of the dashboard toolbar by splitting the combined control panel into a top-level `content-curator-actions-bar` (holding the manual scraping trigger controls, the timeframe dropdown, bulk actions, and the language switcher) and a lower-level `content-curator-filters-bar` (wrapping the view filters for hours, sites, and start/end dates).
  - Cleaned up form structures, removing fetch components from the grid filters `<form>` so they don't trigger unnecessary submissions.
- **assets/css/admin-style.css**:
  - Added dedicated stylesheet rules for `.content-curator-actions-bar` and `.content-curator-filters-bar` to achieve premium visual spacing and visual contrast (with a soft `#fafafb` shading on filters).
  - Styled the count indicator as a high-contrast pill-shaped badge (`.content-curator-count-badge`) with subtle border shadows and primary brand colors.
  - Implemented premium CSS gradient styling for the manual scraper button (`.fetch-now-btn`) with slide-up micro-animations on hover.
  - Included select styling for filter dropdowns within the new filter bar container.
  - Updated responsive query rules to stack actions and filter elements vertically on mobile viewports.
- **wp-content-curator.php**:
  - Bumped version to `1.5.5`.
- **README.md**:
  - Documented version `1.5.5` changes.
- **AGENTS.md**:
  - Documented the new Segmented Control & Filter Bars capability.

## 2026-06-15 - 17:08 - Reverted version 1.5.6 changes to restore version 1.5.5

### Summary of Changes
- Reverted all changes introduced in version 1.5.6 by performing a hard reset to the previous stable state (commit `02ff3d550479fdbd456b5c381d96e6813174618d`).
- Restored the general monitored Page IDs/URLs text field in Settings.
- Removed the Facebook page connection "Test" buttons from the agenda defaults settings table.
- Re-established separate page monitoring configuration inputs and restored background cron / manual fetch routines back to version 1.5.5 functionality.

## 2026-06-15 - 17:23 - Cleaned up language switcher, manual actions, and unified monitored pages in version 1.5.6

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Removed the plugin language switcher dropdown layout from the curation dashboard actions bar.
  - Removed the `wp_ajax_content_curator_change_plugin_lang` AJAX handler registration and `ajax_change_plugin_lang()` method definition.
  - Removed the "Manual Actions" section from the bottom of the settings page and relocated the "Next scheduled fetch" status string into the CRON tab content.
  - Unified monitored Facebook Pages: removed `content_curator_page_ids` basic setting registration, settings field, and rendering method.
  - Updated `ajax_get_pages_to_fetch()` to extract monitored pages directly from the `content_curator_agenda_defaults` table.
  - Updated UI dictionaries (English, Spanish, French) to rename "Event Configuration" tab to "Pages & Events" and clarify that this table manages monitored page URLs/usernames.
- **assets/js/admin-script.js**:
  - Removed the jQuery change listener for `#content-curator-plugin-lang-select`.
- **includes/class-content-curator-cron.php**:
  - Modified background cron fetcher `run_fetch()` to extract monitored page IDs directly from `content_curator_agenda_defaults`.
- **wp-content-curator.php**:
  - Bumped version to `1.5.6`.
- **README.md** & **AGENTS.md**:
  - Updated configuration setup guides and capability definitions to match the unified "Pages & Events" setting.

## 2026-06-15 - 17:28 - Compressed clean production files into wp-content-curator.zip

### Summary of Changes
- Generated the clean release zip package `wp-content-curator.zip` containing a nested `wp-content-curator/` directory structure with only production-ready files (`wp-content-curator.php`, `assets/`, `includes/`, and `README.md`), ignoring Git files, error logs, and IDE local cache metadata.

## 2026-06-15 - 17:50 - Replaced AI model text inputs with dropdown selects

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - `render_field_openai_model()`: Replaced free-text `<input>` with a grouped `<select>` offering GPT-4o, GPT-4o mini (recommended), GPT-4 Turbo, GPT-3.5 Turbo, o1-mini, and o3-mini.
  - `render_field_anthropic_model()`: Replaced free-text `<input>` with a grouped `<select>` covering Claude 3.5 Sonnet, Claude 3.5 Haiku (recommended), Claude 3 Opus, Claude 3 Sonnet, and Claude 3 Haiku.
  - `render_field_gemini_model()`: Replaced free-text `<input>` with a grouped `<select>` covering Gemini 2.0 Flash (recommended), Gemini 2.0 Flash Lite, Gemini 1.5 Pro, Gemini 1.5 Flash, and Gemini 1.5 Flash 8B.
  - Updated description strings in all three language dictionaries (EN, ES, FR) to reflect the new dropdown UX.
  - Existing saved values are pre-selected correctly via `selected()` helper; unknown legacy values will fall back gracefully to the default option.
## 2026-06-15 - 18:00 - Added Image Import Mode setting

### Summary of Changes
- **includes/class-content-curator-admin.php**:
  - Added image_mode strings to EN, ES and FR dictionaries.
  - Registered content_curator_image_mode setting (default: all).
  - Added add_settings_field in the AI Configuration section.
  - Added render_field_image_mode(): radio group with three options (none / featured / all).
- **includes/class-content-curator-cron.php**:
  - Read content_curator_image_mode once before the pending posts loop.
  - none: skips image URL parsing, posts created with no images.
  - featured: trims image_urls to only the first URL, only featured thumbnail set.
  - all (default): existing behaviour preserved, featured image + wp:gallery block.

## 2026-06-15 - 18:50 - Moved image mode decision to dashboard (per-card checkboxes)

### Summary of Changes
- Removed the global content_curator_image_mode setting from Settings (register_setting, add_settings_field, render_field_image_mode and all i18n strings in EN/ES/FR dictionaries).
- Reverted cron image_mode logic (get_option call and featured-only trim removed).
- The decision of which images to import is already handled per-card in the dashboard via the existing include-cover-checkbox and include-gallery-checkbox controls, which are sent as include_cover and include_gallery params to the ajax_publish handler.

## 2026-06-15 - 19:05 - Fixed gallery checkbox overlap in dashboard cards

### Summary of Changes
- **includes/class-content-curator-admin.php**: Moved .image-toggles div outside .card-image-gallery-container. The gallery-controls bar uses position:absolute bottom:0 inside a container with overflow:hidden, which caused it to render on top of the checkboxes. Moving the toggles to be a sibling element after the container resolves the overlap.

## 2026-06-15 - 19:10 - Bump to version 1.5.7

### Summary of Changes
- **wp-content-curator.php**: Version bumped from 1.5.6 to 1.5.7.
- **AGENTS.md**: Updated with new capabilities introduced in this session: AI model dropdowns for all three providers, per-card image import checkboxes (include cover / include gallery), and gallery controls overlap fix.

## 2026-06-15 - 21:30 - Updated documentation and added Spanish translation of README

### Summary of Changes
- **README.md**: Updated documentation with comprehensive details on the features up to version 1.5.7, configuration sections, requirements, and full changelog.
- **README.es.md**: Created the Spanish version of the README documentation.

## 2026-06-15 - 21:40 - Changed image toggles layout to horizontal and bumped version to 1.5.8

### Summary of Changes
- **includes/class-content-curator-admin.php**: Modified inline styles of the `.image-toggles` div in curation cards from `flex-direction: column` to `flex-direction: row` with wrap behavior, positioning the cover image and gallery checkboxes side-by-side to prevent overlaps.
- **wp-content-curator.php**: Version bumped from 1.5.7 to 1.5.8.
- **README.md** & **README.es.md**: Documented layout changes and bumped versions in the changelog section.

## 2026-06-15 - 21:45 - Merged Spanish translation into main README.md

### Summary of Changes
- **README.md**: Appended the complete Spanish translation of the documentation inside the main `README.md` file, adding a navigation header for easy switching between English and Spanish on GitHub.
- **README.es.md**: Deleted the separate Spanish translation file to keep the repository unified and clean.
