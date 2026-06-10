# WP Content Curator

A WordPress plugin that acts as a content curation panel. It connects to the Apify Facebook Posts Scraper API, fetches posts from configured public Pages, stores them locally, and provides an admin dashboard for reviewing, AI-rewriting, editing, and publishing content as native WordPress posts.

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL / MariaDB
- An Apify Account with a valid API Token
- (Optional) OpenAI or Anthropic API key for AI-powered text rewriting

## Installation

1. Download or clone this repository into your WordPress plugins directory:
   ```
   wp-content/plugins/wp-content-curator/
   ```

2. Activate the plugin from the **Plugins** menu in WordPress admin.

3. Navigate to **FB Curator → Settings** to configure your API credentials.

## Configuration

### Apify & Page Setup

1. **Create an Apify Account**:
   - Go to [apify.com](https://apify.com/) and register for a free account.
   - The free plan includes **$5 USD of compute credits monthly**, which automatically renews. Each scraping job consumes a minimal fraction of a cent (approx. $0.005 to $0.01 per run), meaning you can monitor 3-4 pages multiple times a day completely free of charge.

2. **Retrieve your Apify API Token**:
   - Log into the Apify Console.
   - Go to **Settings** (bottom left sidebar) → click on the **Integrations** tab.
   - Locate the **API Tokens** section and copy your active token.
   - Paste this token in WordPress under **FB Curator → Settings → Apify API Token**.

3. **Configure Facebook Pages**:
   - In the **Facebook Page URLs / Usernames** settings field, enter a comma-separated list of the public Facebook Pages you want to curate.
   - **Supported formats**:
     - Full URL: `https://www.facebook.com/techcrunch` or `https://www.facebook.com/profile.php?id=12345678`
     - Username: `techcrunch` (the plugin automatically expands usernames to full Facebook URLs).
   - The plugin communicates directly with the `apify/facebook-posts-scraper` actor using your token. You do not need to install or configure the actor manually inside Apify.

### AI Rewriting Setup (Optional)

1. Choose your preferred AI provider: **OpenAI** (gpt-4o-mini) or **Anthropic** (claude-3-haiku).
2. Enter your API key in the **AI API Key** field.
3. The AI rewrites text into SEO-optimized blog articles with H2 headings, removing social media hashtags.

## Usage

### Automatic Fetching

The plugin automatically fetches new posts from your configured Facebook Pages **every 12 hours** via WP-Cron. New posts are stored in a custom database table with `pending` status.

### Manual Fetching

Go to **FB Curator → Settings** and click **Fetch Now** to trigger an immediate fetch without waiting for the next cron cycle.

### Curation Dashboard

Navigate to **FB Curator → Dashboard** to see all pending posts:

1. **Filter** posts by time range: Last 24 hours, 48 hours, 7 days, or all pending.
2. **Review** each post card showing the source page, date, image thumbnail, and original text.
3. **Edit** the text directly in the built-in textarea editor.
4. **Optimize with AI** (optional) — click the blue button to rewrite the text using your configured AI provider.
5. **Save as Draft** or **Publish** — creates a native WordPress post with the edited text and downloads the Facebook image as a local featured image.

### Post Processing

When you save or publish a post:
- The Facebook image is downloaded and stored in your WordPress Media Library.
- A new WordPress post is created with the edited title and content.
- The image is set as the post's featured image.
- The curated entry is marked as `processed` and removed from the dashboard.

## WP-Cron Reliability

WordPress cron is "pseudo-cron" — it runs on page loads. For low-traffic sites, consider setting up a real server crontab:

```bash
# Run WordPress cron every 15 minutes via system crontab
*/15 * * * * curl -s https://your-site.com/wp-cron.php?doing_wp_cron > /dev/null 2>&1
```

Add this to your `wp-config.php` to disable the default page-load trigger:

```php
define('DISABLE_WP_CRON', true);
```

## File Structure

```
wp-content-curator/
├── wp-content-curator.php          # Main plugin file (bootstrap)
├── includes/
│   ├── class-content-curator-db.php      # Custom database table management
│   ├── class-content-curator-api.php     # Facebook + AI API connectors
│   ├── class-content-curator-cron.php    # WP-Cron scheduling
│   └── class-content-curator-admin.php   # Admin pages, settings, AJAX handlers
├── assets/
│   ├── css/
│   │   └── admin-style.css          # Admin panel styles
│   └── js/
│       └── admin-script.js          # AJAX interaction logic
└── README.md                        # This file
```

## Security

- All AJAX endpoints validate WordPress nonces and `current_user_can()` capabilities.
- User input is sanitized with `sanitize_textarea_field()` and `wp_kses_post()`.
- Output is escaped with `esc_html()`, `esc_attr()`, and `esc_url()`.
- Database queries use `$wpdb->prepare()` for SQL injection prevention.

## Changelog

### 1.4.0
- Reordered settings page tabs to follow: Basic Configuration, AI Configuration, Event Configuration, and CRON.
- Simplified curation dashboard event fields: removed coordinates and video fields from event card editor, keeping only Start Date, End Date, Location, Categories, and Concellos.
- Simplified settings defaults: replaced start/end hours with date picker fields, added a checkbox option to use the current day's date as default start and end dates.
- Cleaned up settings page HTML nesting structure and fixed a PHP syntax parser error.
- Styled settings defaults table with wide, responsive borders and input fields for premium appearance.

### 1.3.0
- Added Agenda Defaults Configuration settings: a new 4th settings tab displaying a dynamic table where administrators can map specific Facebook Page URLs/usernames to default event start/end times, default venue place (lugar), and default coordinates (coordenadas).
- Dashboard Prefilling: Curation Dashboard event forms now scan for configured default values matching the post's source page, automatically pre-filling event start/end dates (combining post date with default hours), venue location, and coordinates.

### 1.2.3
- Fixed custom post type `agenda` location field integration by saving to the lowercase meta key `'lugar'` instead of `'Lugar'`, aligning it with JetEngine and preventing the field from saving blank.

### 1.2.2
- Fixed a syntax parse error in the settings page render method where a trailing comment block commented out the function definition.

### 1.2.1
- Relocated the "Optimize with AI" button directly below the tabbed post editor textareas inside the curation card for improved user workflow.
- Updated styling for the newly relocated button to maintain premium gradients, icons, and hover effects.

### 1.2.0
- Added multi-image support: the plugin now extracts all unique valid image URLs from Facebook posts, stores them as a JSON array, and renders a slide-show gallery inside dashboard cards.
- Multi-image publishing: when saving or publishing posts with multiple images, the plugin downloads and attaches all images, setting the first as the Featured Image and appending a native Gutenberg gallery block to the content.
- Integrated WordPress 7.0 Native AI Client: dynamically registers the provider-agnostic native core AI client, utilizing configured site connectors for text rewriting without needing to insert local API credentials inside the plugin.

### 1.1.9
- Fixed image extraction logic by filtering candidate URLs through a validation helper, ensuring Facebook post/story/video HTML links are ignored and only raw CDN images (e.g. `fbcdn.net` hosts) or common image file extensions are stored.

### 1.1.8
- Added a "Delete All Pending" button next to "Fetch Now" in the dashboard toolbar for bulk curation clearing.
- Changed the text color of the delete buttons (both card and bulk) to black for better contrast and consistency.

### 1.1.7
- Fixed date mapping and parsing bug where fetched posts displayed "Ene 1, 1970" on the dashboard due to incorrect Apify schema references and UNIX timestamp string evaluation.

### 1.1.6
- Added "Delete" button to post cards on the Curation Dashboard.
- Implemented complete database row removal for deleted posts so they can be re-fetched and downloaded again in subsequent runs.

### 1.1.5
- Implemented robust `extract_image_url` logic supporting nested media arrays, direct images arrays, image_urls arrays, and attachments schemas from Apify datasets.
- Added pagination (12 cards per page) to the Curation Dashboard.
- Added custom "From" and "To" date filters to filter pending posts.
- Added "Fetch Now" manual trigger button directly to the Curation Dashboard toolbar with automatic page reload upon success.

### 1.1.4
- Expanded media mapping logic to handle multiple image formats from Apify scrapers (thumbnail, media strings/arrays, and attachments).

### 1.1.3
- Added a dropdown selector to filter pending cards by site/page name in the Curation Dashboard.
- Changed action buttons text color to black for better readability.

### 1.1.2
- Fixed a case-sensitivity issue in option names and AJAX action hooks causing settings and fetches to fail.

### 1.1.1
- Reverted custom admin sidebar menu icon to standard WordPress Dashicon to prevent visual layout bugs.

### 1.1.0
- Migrated data fetching from the official Facebook Graph API to Apify Facebook Posts Scraper.
- Updated settings UI for Apify token configuration.
- Support both full Facebook URLs and usernames for page monitoring.

### 1.0.0
- Initial release.
- Facebook Graph API v20.0 integration.
- Dual AI provider support (OpenAI / Anthropic).
- Curation dashboard with card-based UI.
- Image sideloading to WordPress Media Library.
- WP-Cron automated fetching every 12 hours.

## License

GPL-2.0-or-later
