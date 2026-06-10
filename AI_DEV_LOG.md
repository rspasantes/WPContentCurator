# AI Development Log - WP Content Curator

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