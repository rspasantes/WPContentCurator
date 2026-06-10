# AI Agent Context and Workspace Reference

This file serves as a persistent context helper for AI agents working on the **WP Content Curator** codebase. It contains project goals, environmental details, active plugins, and development guidelines.

## 📋 Project Summary
**WP Content Curator** is a WordPress plugin that implements a curation dashboard. It automatically (and manually) fetches public posts from configured Facebook Page URLs/usernames via the **Apify Facebook Posts Scraper API**. These posts are stored in a custom database table.
Administrators can then review the pending posts in a premium curation panel, rewrite their content using configured AI models (OpenAI, Anthropic, Gemini, or a WordPress 7.0 Native AI Client), and save or publish them as native WordPress posts.
During publication, the plugin automatically sideloads all post images to the Media Library, assigns the first image as the Featured Image, and inserts a Gutenberg gallery block for any additional images.

### Key Additional Capabilities
- **UI Languages**: Multi-language control system with built-in translations for **English**, **Spanish**, and **French** UI texts, buttons, and alert notices. A settings field and dynamic switcher in the toolbar allow instant translation of the interface.
- **Custom Post Types**: Allows selecting which Post Type (e.g., standard post, page, or dynamic Custom Post Types) to create the draft or published post as.
- **CPT Agenda Integration**: Specifically integrates and supports the custom post type `agenda` (Agenda). When selected, it displays dynamic fields for event dates (`fecha-de-inicio` and `fecha-de-fin` stored as UNIX timestamps), Location (`Lugar`), Coordinates (`coordenadas`), Video URL (`video-del-evento`), Event Description (`descripcion-del-evento`), and Event Gallery (`galeria-del-evento` stored as comma-separated sideloaded attachment IDs). It also queries and renders selectors for `categorias-agenda` (hierarchical) and `concellos-eventos` taxonomies and associates them.
- **Taxonomy Tag Selection**: Automatically queries all active non-hierarchical taxonomies (tags) in the system, lists them grouped by taxonomy in a card dropdown, and assigns the selected tag dynamically to the created post.
- **WPML Integration & Tabbed Curation Editor**: Fully integrated with WPML programmatically. Instead of a single post language selector, each curation card displays a tabbed content editor representing each curated language (from settings). This allows concurrent preview and edits of translations. Upon publication or draft creation, individual posts are programmatically created for each language tab and linked together as translations in WPML.
- **External CRON Trigger**: A configurable URL endpoint protected by a secret token that automatically processes pending database posts, translates the content into each active WPML language using the configured AI provider, inserts the master and translated posts, and links them programmatically.
- **Default Post Type & Tag Settings**: General options in settings to configure a default Custom Post Type and default taxonomy Tag for curation card selectors, pre-selecting them automatically upon loading the dashboard.

## 🔗 External Dependencies & Integrations

The plugin relies on or integrates with the following external APIs and third-party WordPress plugins:

### 1. External APIs & AI Providers
- **Apify API (Facebook Posts Scraper)**: Utilized to scrape posts from public Facebook Pages synchronously. Requires an Apify API Token (`content_curator_apify_token`).
- **OpenAI API**: Used for AI rewriting using `gpt-4o-mini` (requires API Key).
- **Anthropic API**: Used for AI rewriting using `claude-3-haiku` (requires API Key).
- **Google Gemini API**: Used for AI rewriting using `gemini-1.5-flash` (requires API Key).
- **WordPress 7.0 Native AI**: Uses the built-in `wp_ai_client_prompt()` function (requires a WordPress 7.0+ core environment with AI client configured).

### 2. Third-Party Plugin Integrations
- **WPML (WordPress Multilingual CMS)**: Optional but highly integrated. Enables the tabbed multilingual curation editor, dynamic AI translations, automatic cloning of published posts for all active languages, and programmatic translation linkage via WPML hooks (`wpml_active_languages`, `wpml_set_element_language_details`).
- **Crocoblock / JetEngine (Agenda Custom Post Type)**: Used for the custom post type `agenda`. The plugin reads and saves the following custom metadata fields and taxonomies:
  - **Meta fields**: `fecha-de-inicio` (UNIX timestamp), `fecha-de-fin` (UNIX timestamp), `Lugar` (string), `coordenadas` (string), `video-del-evento` (URL), and `galeria-del-evento` (comma-separated attachment IDs).
  - **Taxonomies**: `categorias-agenda` (hierarchical taxonomy) and `concellos-eventos` (non-hierarchical taxonomy).

## 🔌 WordPress Active Plugins List
The target WordPress environment has the following active plugins:

1. **AAA Option Optimizer** - Auto-load option tracking and optimization.
2. **Administrador de archivos WP** - File manager utility.
3. **AI Provider for Google** - AI service connector.
4. **Ally - Web Accessibility & Usability** - Accessibility enhancer.
5. **Analítica** - Site Kit by Google integration.
6. **Angie** - Agentic AI helper for WordPress.
7. **Auto Image Attributes From Filename With Bulk Updater** - Automated image Alt/Title mapping.
8. **Code Snippets** - PHP and JS snippet execution tool.
9. **Custom Post Type Permalinks** - Custom permalinks manager.
10. **Default featured image** - Default fallback featured images.
11. **Editor Masivo - Biblioteca de Medios** - Bulk media library editor sheet.
12. **Elementor** - Page builder.
13. **Elementor Pro** - Page builder (pro features).
14. **Envato Elements** - Template and asset library.
15. **Featured Image in RSS Feed by MailerLite** - Add featured images to RSS.
16. **GDPR Cookie Compliance** - GDPR cookie banners and compliance.
17. **Image Optimizer – Compress, Resize and Optimize Images** - Image compression.
18. **JetBlocks For Elementor** - Elements for headers/footers.
19. **JetElements For Elementor** - Addon pack for Elementor.
20. **JetEngine** - Dynamic content and custom fields manager.
21. **JetEngine - Get attachment file link by ID** - Attachment helper.
22. **JetEngine - Trim string callback** - Callback string trimmer.
23. **JetSearch** - AJAX-powered search features.
24. **JetSmartFilters** - Advanced AJAX filters.
25. **ManageWP - Worker** - Website management worker.
26. **Metricool** - Traffic and audience tracking metrics.
27. **Modular Connector** - Centralized site manager connection.
28. **Motion.page** - GSAP-based animation builder.
29. **Security Ninja (Premium)** - Security monitoring and vulnerability scanner.
30. **Site Mailer** - SMTP mailer setup for deliverability.
31. **Skyboot Custom Icons for Elementor** - Elementor icon expansion pack.
32. **Slim SEO** - SEO meta tagging and optimization.
33. **Stream** - User activity and audit log monitor.
34. **UpdraftPlus Backup/Restore** - Cloud backup manager.
35. **VigIA Analítica, control y visibilidad en IAs** - AI scraping and visibility analytics.
36. **WP All Export Pro** - Data export tool.
37. **WP All Import - JetEngine Add-On** - Dynamic custom field imports.
38. **WP All Import Pro** - XML/CSV file importer.
39. **WP Content Curator** (This plugin) - Curation and posting panel.
40. **WP Sheet Editor - Automations** - Bulk spreadsheet automation.
41. **WP Sheet Editor - JetEngine** - Integration for dynamic fields.
42. **WP Sheet Editor - Post Types (Premium)** - Bulk post spreadsheet editor.
43. **WP Sheet Editor - Taxonomy Terms Pro** - Bulk taxonomy editor.
44. **WP Telegram** - Automatic Telegram notifications.
45. **WPML All Import** - Multilingual dynamic imports.
46. **WPML Export and Import** - Multilingual export/import utilities.
47. **WPML Multilingual CMS** - Core translation framework.
48. **WPML String Translation** - Custom string translation utility.

## 🛠️ Environment Rules & Guidelines
- **Operating System**: Windows. Be mindful of command syntax (e.g. use PowerShell/CMD syntax if running terminal commands).
- **Language**: All code, documentation, and commit messages MUST be in **English**.
- **AI Development Log**: You must write a log entry in [AI_DEV_LOG.md](file:///d:/Dev/WP%20Post%20Curator/wp-content-curator/AI_DEV_LOG.md) every time a block of changes is completed.
  - Log format: `- [YYYY-MM-DD] - [HH:MM] - [Summary of changes in English]`
