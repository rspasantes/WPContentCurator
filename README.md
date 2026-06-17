# WP Content Curator

**Languages / Idiomas:** [English](#wp-content-curator-english) | [Español](#wp-content-curator-español)

---

# WP Content Curator (English)

A premium WordPress plugin that acts as an advanced content curation panel. It connects to the Apify Facebook Posts Scraper API, fetches posts from configured public Pages, stores them locally, and provides an admin dashboard for reviewing, editing, AI-rewriting, translating, scheduling, and publishing content as native WordPress posts or Custom Post Types.

![Plugin Banner](assets/images/banner.png)

## Features

- **Apify Integration**: Fetches public Facebook Page posts using the Apify Facebook Posts Scraper API.
- **Progressive Scraper**: Sequential page-by-page fetching queue in manual fetches to prevent gateway timeouts, displaying progress indicators for each page.
- **Advanced Curation Dashboard**: Segregated Actions Bar (fetching, bulk options, stats badge) and dedicated Curation Filters Bar (filter by timeframe, source page, custom dates).
- **Multi-Language Tabbed Curation Editor**: Programmatic WPML integration allowing concurrent preview, title customization, and content editing of translations in separate tabs.
- **Dynamic AI Optimization & Translation**: Support for OpenAI (GPT-4o, GPT-4o mini, etc.), Anthropic (Claude 3.5 Sonnet/Haiku, etc.), Google Gemini (2.0 Flash, etc.), and WordPress 7.0 Native AI Client.
- **AI Settings Connectivity Test**: Built-in test button inside settings to validate AI keys and API connection instantly before saving.
- **CPT Agenda Integration**: Specialized support for JetEngine's `agenda` Custom Post Type, including custom event dates, venues (location), and taxonomies (`categorias-agenda` and `concellos-eventos`).
- **Flexible Image Sideloading**: Per-card checkboxes to toggle cover image (sets Featured Image, automatically renamed using post slug) and Gutenberg gallery blocks.
- **Scheduling**: Future publishing date/time selector directly on curation cards.
- **External CRON Endpoint**: Safe webhook endpoint protected by a secret token to automate fetching, AI-rewriting, translating, and publishing.

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL / MariaDB
- An Apify Account with a valid API Token
- *(Optional)* OpenAI, Anthropic, or Google Gemini API Key for AI rewriting and translation
- *(Optional)* WPML (WordPress Multilingual CMS) for multilingual curation and post linking
- *(Optional)* JetEngine (Crocoblock) for `agenda` Custom Post Type integration

## Installation

1. Download or clone this repository into your WordPress plugins directory:
   ```
   wp-content/plugins/wp-content-curator/
   ```
2. Activate the plugin from the **Plugins** menu in WordPress admin.
3. Navigate to **Content Curator → Settings** to configure credentials.

## Configuration

The settings area is divided into four main tabs:

### 1. Basic Configuration
- **Default Post Type**: Pre-selects the default post type (Post, Page, Agenda, etc.) in curation cards.
- **Default Tag**: Selects a default non-hierarchical taxonomy tag to automatically pre-fill cards.
- **Curated Languages**: Checkboxes to select which active WPML languages should appear as tabs in the curation editor.

### 2. AI Configuration
- **AI Provider**: Choose between OpenAI, Anthropic, Google Gemini, or WordPress Native AI.
- **AI API Key**: Insert your provider's API key.
- **AI Model**: Select from a grouped dropdown of recommended models (e.g., `gpt-4o-mini`, `claude-3-5-haiku`, `gemini-2.0-flash`).
- **System Prompt**: Customize the instructions sent to the AI for rewriting and translation.
- **Test AI Service**: Click **Test AI Service** to test credentials and connectivity instantly without saving first.

### 3. Pages & Events
- **Apify API Token**: Go to [apify.com](https://apify.com/), create an account, copy your API token from Integrations, and paste it here.
- **Monitored Pages & Event Defaults**: A unified table mapping all monitored Facebook Page URLs or usernames. For each page, you can configure:
  - **Facebook Page URL/Username**: Standard format (e.g., `techcrunch` or full URL).
  - **Use Today**: Checkbox to use the current date as default.
  - **Default Start/End Dates**: Fallback event dates if the page represents events.
  - **Default Venue**: Pre-filled location text for the event.

### 4. CRON
- **Enable External CRON**: Toggle the safe endpoint.
- **CRON Token**: Secure string used to authenticate external webhook calls.
- **External CRON Trigger URL**: Copyable URL to trigger automatic processing. Example:
  ```
  https://your-site.com/wp-cron.php?doing_wp_cron
  ```
  *(Or use the direct hook URL provided in the CRON settings tab with curl)*

## Usage

### Curation Dashboard Layout
- **Toolbar Actions**: Scan now (with timeframe filter: 24 hours, 7 days, all), bulk delete pending posts, and see a counter badge of pending items.
- **Filters**: Filter cards dynamically by timeframe (hours), source page, or custom start/end dates.

### Managing Cards
1. **Split Editor**: Each card splits the Facebook text into a custom **Post Title** (editable) and **Content** body.
2. **Multilingual Tabs**: If WPML is active, toggle between language tabs. Editing or optimizing updates all tabs.
3. **Optimize with AI**: Click the button below the editor to clean, rewrite, and automatically translate content into all selected languages.
4. **Publishing Event (Agenda)**: If `agenda` CPT is selected, event-specific selectors appear (Start/End dates, Location, hierarchical Categories, non-hierarchical Concellos).
5. **Image Selection**: Toggle cover image (renamed to sanitized post title slug) or gallery block import.
6. **Save Draft / Publish**: Triggers a premium glassmorphic modal displaying success messages and direct edit/view links.

## File Structure

```
wp-content-curator/
├── wp-content-curator.php          # Main bootstrap file
├── includes/
│   ├── class-content-curator-db.php      # Custom database table management
│   ├── class-content-curator-api.php     # Facebook + AI API connectors
│   ├── class-content-curator-cron.php    # Cron scheduling & webhook handlers
│   └── class-content-curator-admin.php   # Settings, layouts, and AJAX hooks
├── assets/
│   ├── css/
│   │   └── admin-style.css          # Premium stylesheet definitions
│   ├── js/
│   │   └── admin-script.js          # Interactive AJAX actions & scripts
│   └── images/
│       ├── icon.png                 # Plugin menu icon
│       └── banner.png               # Admin header banner image
└── README.md                        # English documentation
```

## Security

- All AJAX endpoints validate WordPress nonces and `current_user_can()` capabilities.
- User input is sanitized with `sanitize_textarea_field()` and `wp_kses_post()`.
- Output is escaped with `esc_html()`, `esc_attr()`, and `esc_url()`.
- Database queries use `$wpdb->prepare()` for SQL injection prevention.

---

# WP Content Curator (Español)

Un plugin premium de WordPress que funciona como un panel avanzado de curación de contenido. Se conecta a la API de Apify Facebook Posts Scraper, recupera publicaciones de páginas públicas configuradas, las almacena localmente y proporciona un panel de administración para revisar, editar, reescribir con IA, traducir, programar y publicar contenido como entradas nativas de WordPress o Tipos de Contenido Personalizados (CPT).

## Características

- **Integración con Apify**: Recupera publicaciones de páginas de Facebook utilizando la API de Apify Facebook Posts Scraper.
- **Escaneado Progresivo**: Cola de importación secuencial página por página en ejecuciones manuales para evitar tiempos de espera del servidor, mostrando indicadores de progreso en tiempo real.
- **Panel de Curación Avanzado**: Barra de Acciones separada (controles de importación, opciones por lotes y medidor de estadísticas) y una Barra de Filtros dedicada (rango de fechas, página de origen y horas).
- **Editor de Curación Multilingüe por Pestañas**: Integración programática con WPML que permite previsualizar de forma concurrente, personalizar títulos y editar el contenido de las traducciones en pestañas separadas.
- **Optimización y Traducción con IA**: Soporte nativo para OpenAI (GPT-4o, GPT-4o mini, etc.), Anthropic (Claude 3.5 Sonnet/Haiku, etc.), Google Gemini (2.0 Flash, etc.) y el cliente nativo de IA de WordPress 7.0.
- **Prueba de Conexión de IA**: Botón de prueba integrado en los ajustes para validar las claves de API y comprobar la conexión al instante antes de guardar los cambios.
- **Integración con CPT Agenda**: Soporte especializado para el Tipo de Contenido Personalizado `agenda` de JetEngine (Crocoblock), incluyendo fechas del evento, lugar de celebración y las taxonomías `categorias-agenda` y `concellos-eventos`.
- **Importación de Imágenes Flexible**: Selectores en cada tarjeta para activar o desactivar la imagen de portada (se descarga como Imagen Destacada, renombrándose con el slug del título) y bloques de galería Gutenberg.
- **Planificación**: Selector de fecha y hora directamente en las tarjetas de curación para programar publicaciones a futuro.
- **Endpoint de CRON Externo**: Endpoint seguro protegido por un token secreto para automatizar la importación, reescritura, traducción y publicación de contenidos.

## Requisitos

- WordPress 6.0 o superior
- PHP 8.0 o superior
- MySQL / MariaDB
- Una cuenta de Apify con un Token de API válido
- *(Opcional)* Clave de API de OpenAI, Anthropic o Google Gemini para reescritura y traducción con IA
- *(Opcional)* WPML (WordPress Multilingual CMS) para curación multilingüe y vinculación de traducciones
- *(Opcional)* JetEngine (Crocoblock) para la integración del Tipo de Contenido Personalizado `agenda`

## Instalación

1. Descarga o clona este repositorio en el directorio de plugins de tu WordPress:
   ```
   wp-content/plugins/wp-content-curator/
   ```
2. Activa el plugin desde el menú de **Plugins** en el panel de administración de WordPress.
3. Ve a **Content Curator → Ajustes** para configurar las credenciales.

## Configuración

El área de ajustes está dividida en cuatro pestañas principales:

### 1. Configuración Básica
- **Tipo de Contenido por Defecto**: Preselecciona el tipo de contenido (Entrada, Página, Agenda, etc.) en las tarjetas de curación.
- **Etiqueta por Defecto**: Selecciona una etiqueta de taxonomía no jerárquica para pre-rellenar las tarjetas automáticamente.
- **Idiomas de Curación**: Casillas para elegir qué idiomas activos de WPML aparecerán como pestañas en el editor de curación.

### 2. Configuración de IA
- **Proveedor de IA**: Elige entre OpenAI, Anthropic, Google Gemini o WordPress Native AI.
- **Clave de API de IA**: Introduce la clave de API de tu proveedor.
- **Modelo de IA**: Elige entre un menú desplegable con opciones recomendadas (p. ej., `gpt-4o-mini`, `claude-3-5-haiku`, `gemini-2.0-flash`).
- **System Prompt**: Personaliza las instrucciones que se envían a la IA para la reescritura y traducción.
- **Probar Servicio de IA**: Haz clic en **Probar Servicio de IA** para validar las credenciales y la conexión de inmediato sin necesidad de guardar primero.

### 3. Páginas y Eventos
- **Token de API de Apify**: Regístrate en [apify.com](https://apify.com/), copia tu token de API en la pestaña de Integraciones y pégalo aquí.
- **Páginas Monitoreadas y Valores por Defecto**: Una tabla unificada que mapea todas las URLs o nombres de usuario de las páginas de Facebook. Para cada página se puede configurar:
  - **URL/Username de la Página de Facebook**: Formato estándar (p. ej., `techcrunch` o la URL completa).
  - **Usar Hoy**: Casilla para usar el día actual como fecha por defecto.
  - **Fechas por Defecto (Inicio/Fin)**: Fechas de respaldo si la página representa un evento.
  - **Lugar por Defecto**: Texto pre-rellenado para la ubicación del evento.

### 4. CRON
- **Activar CRON Externo**: Habilita el endpoint seguro.
- **Token de CRON**: Cadena segura para autenticar las llamadas de webhook externas.
- **URL de Disparador CRON Externo**: URL copiable para ejecutar el proceso automático. Ejemplo:
  ```
  https://tu-sitio.com/wp-cron.php?doing_wp_cron
  ```
  *(O utiliza la URL directa del webhook provista en la pestaña CRON usando curl)*

## Uso

### Estructura del Panel de Curación
- **Acciones de la Barra**: Escanear ahora (con filtro de tiempo: 24 horas, 7 días, todo el tiempo), borrar todo lo pendiente y ver un contador con los elementos pendientes.
- **Filtros**: Filtra las tarjetas dinámicamente por rango de tiempo (horas), página de origen o fechas de inicio y fin personalizadas.

### Gestión de Tarjetas
1. **Editor Dividido**: Cada tarjeta separa el texto original de Facebook en un **Título de Entrada** (editable) y el **Contenido** principal.
2. **Pestañas Multilingües**: Si WPML está activo, navega entre las pestañas de idioma. Al editar u optimizar con IA, se actualizarán todas las pestañas simultáneamente.
3. **Optimizar con IA**: Haz clic en el botón debajo del editor para limpiar, reescribir y traducir automáticamente el contenido a todos los idiomas seleccionados.
4. **Campos de Evento (CPT Agenda)**: Si se selecciona el tipo de contenido `agenda`, aparecerán campos de evento dinámicos (Fechas de inicio/fin, Lugar, Categorías de Agenda jerárquicas y Concellos no jerárquicos).
5. **Selección de Imágenes**: Elige si deseas importar la imagen de portada (descargada como Imagen Destacada y renombrada con el slug del título) o el bloque de galería de imágenes.
6. **Guardar Borrador / Publicar**: Abre un modal premium con efecto esmerilado que muestra mensajes de éxito y enlaces directos para editar o ver la publicación.

## Estructura de Archivos

```
wp-content-curator/
├── wp-content-curator.php          # Archivo de inicio del plugin (bootstrap)
├── includes/
│   ├── class-content-curator-db.php      # Gestión de la tabla de base de datos
│   ├── class-content-curator-api.php     # Conectores de APIs de Facebook e IA
│   ├── class-content-curator-cron.php    # Planificador Cron y webhook externo
│   └── class-content-curator-admin.php   # Ajustes, maquetación y hooks de AJAX
├── assets/
│   ├── css/
│   │   └── admin-style.css          # Estilos premium del panel de administración
│   ├── js/
│   │   └── admin-script.js          # Acciones interactivas AJAX y scripts
│   └── images/
│       ├── icon.png                 # Icono de menú del plugin
│       └── banner.png               # Banner de cabecera del panel
└── README.md                        # Documentación en inglés
```

## Seguridad

- Todos los endpoints de AJAX validan nonces de WordPress y capacidades mediante `current_user_can()`.
- Los datos de entrada del usuario son saneados con `sanitize_textarea_field()` y `wp_kses_post()`.
- Los datos de salida se escapan adecuadamente mediante `esc_html()`, `esc_attr()` y `esc_url()`.
- Las consultas a la base de datos utilizan `$wpdb->prepare()` para prevenir la inyección de SQL.

---

## Changelog

### 1.6.5
- **Specific Publication Status**: Updated the History list table and CSV export to display specific publication statuses (Published, Draft, Scheduled, Ignored, or Processed) based on database status and linked WordPress post status.
- **WordPress Direct Linkage**: Appended a direct link to the published post (permalink) or draft/scheduled post (WordPress editor link) inside the "URL Original" list table column and as a dedicated "URL WordPress" column in the CSV export (if the post was not ignored).
- **Badge Styling**: Styled new status badges (`cc-badge-draft`, `cc-badge-future`, `cc-badge-publish`) with distinct and harmonious colors.
- **Version Bump**: Bumped plugin version to 1.6.5.

### 1.6.4
- **Dynamic AI Settings Fields**: Integrated show/hide toggles for AI setting fields using jQuery on the Settings page. Selecting an AI Provider (OpenAI, Anthropic, Gemini) dynamically displays only its corresponding Model dropdown and the API Key field, while selecting WordPress 7 Native AI hides them all.
- **Version Bump**: Bumped plugin version to 1.6.4.

### 1.6.3
- **Export Emojis Stripping**: Modified the CSV history exporter to automatically strip emoji characters and visual symbols from Title and Content fields.
- **Detailed History Columns**: Expanded both the History list table UI and the CSV export to display 11 detailed columns: ID, URL Original, Titular, Contenido, Tipo de evento, Etiquetas, Fecha Publicación Original, Fecha Publicación, Lugar, Categorias, and Concellos.
- **WordPress Post Meta Linkage**: Implemented `_fb_post_id` post meta storage during manual and auto-curation publishing to link database records dynamically with created WordPress entries.
- **Version Bump**: Bumped plugin version to 1.6.3.

### 1.6.2
- **Ignore Curation Action**: Changed the curation card's 'Eliminar' (Delete) action behavior to update the database post status to 'ignored' instead of deleting the row. This preserves the record in the database, preventing it from being scraped again, and correctly lists it in the History page as 'Ignored'.
- **Ignore All Curation Action**: Updated the 'Delete All Pending' button to update all pending posts' statuses to 'ignored' instead of deleting them.
- **Version Bump**: Bumped plugin version to 1.6.2.

### 1.6.1
- **Export Filter Fix**: Modified the CSV export action to fetch live selected values from the filter form dropdowns and input fields dynamically via jQuery instead of relying on cached static page-load attributes.
- **Version Bump**: Bumped plugin version to 1.6.1.

### 1.6.0
- **Publication History**: New **History** submenu page showing all processed and ignored posts with filtering by status, source page, and date range. Paginated table with status badges, text preview modal, and per-row "Re-queue" action to return posts to the pending queue.
- **CSV Export**: Export to CSV button (Excel-compatible, UTF-8 BOM) on the History page, exporting all filtered results including original text, status, and dates.
- **Gallery Layout Fix**: Moved the gallery navigation bar (`1 / N`) out of the `overflow: hidden` image container so it no longer overlaps the cover/gallery checkboxes below it. The image, nav bar, and toggles now stack cleanly in document flow.

### 1.5.8
- **Layout Alignment**: Adjusted the curation card's image toggle checkboxes (cover image and gallery) to align side-by-side horizontally instead of stacked vertically, preventing visual overlapping.
- **Version Bump**: Bumped plugin version to 1.5.8.

### 1.5.7
- **AI Model Dropdowns**: Upgraded OpenAI, Anthropic, and Gemini inputs in Settings to grouped `<select>` dropdowns displaying recommended defaults.
- **Per-Card Checkboxes**: Relocated cover and gallery image checkboxes below the slider to avoid overlapping layout issues.
- **Documentation**: Restructured the main README files to cover all project extensions.

### 1.5.6
- **Unified Pages**: Integrated monitored page configuration directly into the Pages & Events defaults mapping table, removing duplicate settings.
- **Cron Upgrades**: Updated manual fetches and background tasks to extract target sources from the unified defaults table.
- **Settings Cleanup**: Relocated Next scheduled fetch string to the CRON tab and removed manual actions from Settings.

### 1.5.5
- **Segregated Toolbar**: Separated Actions Bar and Curation Filters Bar to resolve layout clutter.
- **Visual Styles**: Added gradient scrapers, hover micro-animations, and styled stats badges.

### 1.5.4
- **CPT Agenda Refinement**: Integrated custom models settings in AI connector, added progressive manual queue fetching, and scheduling selector calendars on cards.

### 1.5.3
- **AI Connection Test**: Added a direct validation button inside AI settings tab.

### 1.5.2
- **Image Import Checklist**: Replaced single gallery checkbox with cover image and gallery block toggles.

### 1.5.1
- **Timeframe Selector**: Restrict manual fetches to 24h, 7d, or all time.

### 1.5.0
- **Editable Title Input**: Split the curation texteditor into separate editable Title and Content fields.

### 1.4.1
- **Featured Image Renaming**: Cover images are automatically renamed to match the sanitized post title slug.

### 1.4.0
- **Simplification**: Removed coordinates and video fields, converting dates to unified inputs.

### 1.3.0
- **Agenda Defaults**: Mapped default venue place and pre-filled times based on source pages.

### 1.2.3
- **Fix**: Corrected lowercase meta key mapping for `'lugar'`.

### 1.2.2
- **Fix**: Resolved PHP parsing parser error in admin settings render.

### 1.2.1
- **Relocation**: Moved AI optimization buttons directly below the language textareas.

### 1.2.0
- **Native AI & Multi-Image**: Extracted multiple images for Gutenberg slideshow galleries. Added WordPress 7 Native AI support.

### 1.1.9
- **Validation**: Filtered Facebook post HTML elements from image CDN scrapers.

### 1.1.8
- **Bulk Delete**: Added "Delete All Pending" button to toolbar.

### 1.1.7
- **Fix**: Solved UNIX timestamp parsing on Apify data.

### 1.1.6
- **Deletion**: Added delete buttons to curation dashboard cards.

### 1.1.5
- **Features**: Pagination, custom date filters, and manual toolbar fetch.

### 1.1.4
- **Scraper Mapping**: Expanded attachments extraction patterns.

### 1.1.3
- **Filters**: Added page source dropdown filter.

### 1.1.2
- **Fix**: Resolved case-sensitivity option bugs.

### 1.1.1
- **Visuals**: Reverted sidebar icons to Dashicons.

### 1.1.0
- **Migration**: Replaced Facebook Graph API with Apify Scraper.

### 1.0.0
- **Initial Release**: Basic scaffold, cron, settings, dashboard, OpenAI/Anthropic.

## License

GPL-2.0-or-later
