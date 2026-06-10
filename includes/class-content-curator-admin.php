<?php
/**
 * content_curator_Admin
 *
 * Handles the WordPress admin interface: menu registration, settings page,
 * curation dashboard rendering, asset enqueueing, and all AJAX handlers.
 *
 * @package WP_Facebook_Curator
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class content_curator_Admin {

    /**
     * Get UI translation dictionary for a specific language.
     *
     * @param string $lang 'en', 'es', or 'fr'.
     * @return array
     */
    public static function get_dictionary( $lang = 'en' ) {
        $dict = array(
            'en' => array(
                'dashboard_title'      => 'Content Curation Dashboard',
                'dashboard_desc'       => 'Review, edit, and optimize your scraped public Facebook Page posts with AI.',
                'time'                 => 'Time:',
                'site'                 => 'Site:',
                'from'                 => 'From:',
                'to'                   => 'To:',
                'clear_filters'        => 'Clear Filters',
                'fetch_now'            => 'Fetch Now',
                'delete_all'           => 'Delete All Pending',
                'all_pending'          => 'All pending',
                'last_24'              => 'Last 24 hours',
                'last_48'              => 'Last 48 hours',
                'last_7d'              => 'Last 7 days',
                'todos'                => 'All',
                'pending_posts'        => 'pending post',
                'pending_posts_plural' => 'pending posts',
                'no_pending'           => 'No pending posts found',
                'no_pending_desc'      => 'Try changing the filters or fetch new posts.',
                'original_text'        => 'Original Text',
                'editor'               => 'Editor',
                'optimize_ai'          => 'Optimize with AI',
                'save_draft'           => 'Save as Draft',
                'publish'              => 'Publish',
                'delete'               => 'Delete',
                'prev'                 => '&laquo; Prev',
                'next'                 => 'Next &raquo;',
                'facebook_image'       => 'Facebook post image',
                'language_label'       => 'Plugin Language:',
                'post_type_label'      => 'Publish as:',
                'post_lang_label'      => 'Post Language:',
                'post_tag_label'       => 'Select Tag:',
                'none_option'          => 'None',
                'standard_post'        => 'Standard Post',
                'all_languages'        => 'All Languages (Translations)',
                // AJAX & Notifications
                'confirm_publish'      => 'Publish this post to your site?',
                'confirm_draft'        => 'Save this post as a draft?',
                'confirm_delete'       => 'Are you sure you want to delete this post? It will be removed from the curation panel and can be fetched again in the next scrape.',
                'confirm_delete_all'   => 'Are you sure you want to delete all pending posts? This will empty your curation queue.',
                'rewriting'            => 'Rewriting with AI...',
                'publishing'           => 'Publishing...',
                'saving_draft'         => 'Saving draft...',
                'deleting'             => 'Deleting...',
                'deleting_all'         => 'Deleting all posts...',
                'fetching'             => 'Fetching posts...',
                'success_publish'      => 'Post published successfully!',
                'success_draft'        => 'Post saved as draft!',
                'success_rewrite'      => 'Text rewritten successfully!',
                'success_delete'       => 'Post deleted successfully!',
                'success_delete_all'   => 'All pending posts deleted successfully!',
                'error_generic'        => 'An error occurred. Please try again.',
                'all_processed'        => 'All posts have been processed!',
                'all_processed_desc'   => 'Great job! Check back later for new content.',
                'post_empty'           => 'Post content cannot be empty.',
                'sideload_err'         => 'Image sideload error',
                'save_settings'        => 'Save Settings',
                'manual_actions'       => 'Manual Actions',
                'manual_fetch_desc'    => 'Trigger a manual fetch without waiting for the next scheduled cron run.',
                'next_sched'           => 'Next scheduled fetch:',
                // Settings headers
                'tab_basic'            => 'Apify & Page Configuration (Basic Config)',
                'tab_ai'               => 'AI Rewriting Configuration (AI Config)',
                'tab_cron'             => 'External CRON Configuration (CRON)',
                'apify_section_title'  => 'Apify & Page Configuration',
                'apify_section_desc'   => 'Enter your Apify API credentials and the Facebook Page URLs/names to monitor.',
                'apify_token_label'    => 'Apify API Token',
                'apify_token_desc'     => 'Your Apify API Token. You can find it in your Apify Console under Settings -> Integrations -> API Tokens.',
                'fb_pages_label'       => 'Facebook Page URLs / Usernames',
                'fb_pages_desc'        => 'Comma-separated list of Facebook Page URLs or usernames.',
                'ai_section_title'     => 'AI Rewriting Configuration',
                'ai_section_desc'      => 'Configure the AI provider for optional text rewriting.',
                'ai_provider_label'    => 'AI Provider',
                'ai_key_label'         => 'AI API Key',
                'ai_key_desc'          => 'Your API key for the selected AI provider.',
                'ai_prompt_label'      => 'System Prompt (AI Instructions)',
                'ai_prompt_desc'       => 'Base instructions sent to the AI when clicking "Optimize with AI".',
                'wp_native_ai'         => 'WordPress 7 Native AI',
                // CRON configuration labels
                'cron_section_title'   => 'External CRON Configuration',
                'cron_section_desc'    => 'Configure the external CRON modality to process pending posts automatically via a URL request.',
                'cron_enable_label'    => 'Enable External CRON',
                'cron_token_label'     => 'Secret Token',
                'cron_limit_label'     => 'Default Limit',
                'cron_status_label'    => 'Default Status',
                'cron_url_label'       => 'CRON URL Trigger',
                'cron_url_desc'        => 'This is the URL that your server or external service should call to trigger the automatic curation process.',
                // Event custom CPT agenda fields
                'event_fields_title'   => 'Event Fields (Crocoblock)',
                'event_start_label'    => 'Start Date & Time',
                'event_end_label'      => 'End Date & Time',
                'event_location_label' => 'Location (Lugar)',
                'event_location_placeholder' => 'Enter location or venue name',
                'event_coords_label'   => 'Coordinates (Coordenadas)',
                'event_coords_placeholder' => 'e.g. 43.123,-8.456',
                'event_video_label'    => 'Event Video URL',
                'categorias_agenda_label' => 'Agenda Categories:',
                'concellos_eventos_label' => 'Concellos (Events):',
                'multiselect_help'     => 'Hold Ctrl (Cmd on Mac) to select multiple options.',
            ),
            'es' => array(
                'dashboard_title'      => 'Panel de Curación de Contenidos',
                'dashboard_desc'       => 'Revisa, edita y optimiza tus publicaciones de Facebook extraídas con Inteligencia Artificial.',
                'time'                 => 'Tiempo:',
                'site'                 => 'Sitio:',
                'from'                 => 'Desde:',
                'to'                   => 'Hasta:',
                'clear_filters'        => 'Limpiar Filtros',
                'fetch_now'            => 'Importar Ahora',
                'delete_all'           => 'Eliminar Todos los Pendientes',
                'all_pending'          => 'Todos los pendientes',
                'last_24'              => 'Últimas 24 horas',
                'last_48'              => 'Últimas 48 horas',
                'last_7d'              => 'Últimos 7 días',
                'todos'                => 'Todos',
                'pending_posts'        => 'publicación pendiente',
                'pending_posts_plural' => 'publicaciones pendientes',
                'no_pending'           => 'No se encontraron publicaciones pendientes',
                'no_pending_desc'      => 'Prueba a cambiar los filtros o importa nuevas publicaciones.',
                'original_text'        => 'Texto Original',
                'editor'               => 'Editor',
                'optimize_ai'          => 'Optimizar con IA',
                'save_draft'           => 'Guardar Borrador',
                'publish'              => 'Publicar',
                'delete'               => 'Eliminar',
                'prev'                 => '&laquo; Anterior',
                'next'                 => 'Siguiente &raquo;',
                'facebook_image'       => 'Imagen de publicación de Facebook',
                'language_label'       => 'Idioma del Plugin:',
                'post_type_label'      => 'Publicar como:',
                'post_lang_label'      => 'Idioma de Entrada:',
                'post_tag_label'       => 'Seleccionar Etiqueta:',
                'none_option'          => 'Ninguno',
                'standard_post'        => 'Entrada Estándar',
                'all_languages'        => 'Todos los idiomas (Traducciones)',
                // AJAX & Notifications
                'confirm_publish'      => '¿Publicar esta entrada en tu sitio?',
                'confirm_draft'        => '¿Guardar esta entrada como borrador?',
                'confirm_delete'       => '¿Estás seguro de que quieres eliminar esta publicación? Se quitará del panel de curación y podrá volver a importarse en la siguiente extracción.',
                'confirm_delete_all'   => '¿Estás seguro de que quieres eliminar todas las publicaciones pendientes? Esto vaciará tu cola de curación.',
                'rewriting'            => 'Reescribiendo con IA...',
                'publishing'           => 'Publicando...',
                'saving_draft'         => 'Guardando borrador...',
                'deleting'             => 'Eliminando...',
                'deleting_all'         => 'Eliminando todas las publicaciones...',
                'fetching'             => 'Importando publicaciones...',
                'success_publish'      => '¡Entrada publicada con éxito!',
                'success_draft'        => '¡Entrada guardada como borrador!',
                'success_rewrite'      => '¡Texto reescrito con éxito!',
                'success_delete'       => '¡Publicación eliminada con éxito!',
                'success_delete_all'   => '¡Todas las publicaciones pendientes fueron eliminadas!',
                'error_generic'        => 'Ha ocurrido un error. Por favor, inténtalo de nuevo.',
                'all_processed'        => '¡Todas las publicaciones han sido procesadas!',
                'all_processed_desc'   => '¡Buen trabajo! Vuelve más tarde para ver contenido nuevo.',
                'post_empty'           => 'El contenido de la entrada no puede estar vacío.',
                'sideload_err'         => 'Error al importar la imagen',
                'save_settings'        => 'Guardar Ajustes',
                'manual_actions'       => 'Acciones Manuales',
                'manual_fetch_desc'    => 'Activa una importación manual sin esperar a la próxima ejecución programada del cron.',
                'next_sched'           => 'Siguiente importación programada:',
                // Settings headers
                'tab_basic'            => 'Configuración de Apify y Páginas (Configuración Básica)',
                'tab_ai'               => 'Configuración de Reescritura por IA (Configuración IA)',
                'tab_cron'             => 'Configuración de CRON Externo (CRON)',
                'apify_section_title'  => 'Configuración de Apify y Páginas',
                'apify_section_desc'   => 'Introduce tus credenciales de Apify y las URLs/nombres de las páginas de Facebook a monitorizar.',
                'apify_token_label'    => 'Token de API de Apify',
                'apify_token_desc'     => 'Tu Token de API de Apify. Puedes encontrarlo en tu Consola de Apify bajo Settings -> Integrations -> API Tokens.',
                'fb_pages_label'       => 'URLs / Nombres de usuario de Páginas de Facebook',
                'fb_pages_desc'        => 'Lista separada por comas de URLs de páginas de Facebook o nombres de usuario.',
                'ai_section_title'     => 'Configuración de Reescritura por IA',
                'ai_section_desc'      => 'Configura el proveedor de IA para la reescritura opcional de texto.',
                'ai_provider_label'    => 'Proveedor de IA',
                'ai_key_label'         => 'Clave de API de IA',
                'ai_key_desc'          => 'Tu clave de API para el proveedor de IA seleccionado.',
                'ai_prompt_label'      => 'System Prompt (Instrucciones IA)',
                'ai_prompt_desc'       => 'Instrucciones base que se enviarán a la IA cada vez que pulses en "Optimizar con IA".',
                'wp_native_ai'         => 'IA Nativa de WordPress 7',
                // CRON configuration labels
                'cron_section_title'   => 'Configuración de CRON Externo',
                'cron_section_desc'    => 'Configura la modalidad de CRON externo para procesar publicaciones pendientes automáticamente a través de una solicitud URL.',
                'cron_enable_label'    => 'Activar CRON Externo',
                'cron_token_label'     => 'Token Secreto',
                'cron_limit_label'     => 'Límite por Defecto',
                'cron_status_label'    => 'Estado por Defecto',
                'cron_url_label'       => 'URL de Activación de CRON',
                'cron_url_desc'        => 'Esta es la URL que tu servidor o servicio externo debe llamar para activar el proceso de curación automática.',
                // Event custom CPT agenda fields
                'event_fields_title'   => 'Campos del Evento (Crocoblock)',
                'event_start_label'    => 'Fecha de inicio',
                'event_end_label'      => 'Fecha de fin',
                'event_location_label' => 'Lugar',
                'event_location_placeholder' => 'Introduce el lugar o dirección',
                'event_coords_label'   => 'Coordenadas',
                'event_coords_placeholder' => 'ej. 43.123,-8.456',
                'event_video_label'    => 'Video del evento',
                'categorias_agenda_label' => 'Categorías agenda:',
                'concellos_eventos_label' => 'Concellos eventos:',
                'multiselect_help'     => 'Mantén presionado Ctrl (Cmd en Mac) para seleccionar varios.',
            ),
            'fr' => array(
                'dashboard_title'      => 'Tableau de Curation de Contenu',
                'dashboard_desc'       => 'Révisez, modifiez et optimisez vos publications Facebook récupérées avec l\'IA.',
                'time'                 => 'Temps:',
                'site'                 => 'Site:',
                'from'                 => 'De:',
                'to'                   => 'À:',
                'clear_filters'        => 'Effacer les Filtres',
                'fetch_now'            => 'Importer Maintenant',
                'delete_all'           => 'Supprimer tous les éléments en attente',
                'all_pending'          => 'Tous en attente',
                'last_24'              => 'Dernières 24 heures',
                'last_48'              => 'Dernières 48 heures',
                'last_7d'              => 'Derniers 7 jours',
                'todos'                => 'Tous',
                'pending_posts'        => 'publication en attente',
                'pending_posts_plural' => 'publications en attente',
                'no_pending'           => 'Aucune publication en attente trouvée',
                'no_pending_desc'      => 'Essayez de modifier les filtres ou d\'importer de nouvelles publications.',
                'original_text'        => 'Texte Original',
                'editor'               => 'Éditeur',
                'optimize_ai'          => 'Optimiser avec l\'IA',
                'save_draft'           => 'Enregistrer le brouillon',
                'publish'              => 'Publier',
                'delete'               => 'Supprimer',
                'prev'                 => '&laquo; Précédent',
                'next'                 => 'Suivant &raquo;',
                'facebook_image'       => 'Image de la publication Facebook',
                'language_label'       => 'Langue du Plugin:',
                'post_type_label'      => 'Publier sous:',
                'post_lang_label'      => 'Langue de l\'article:',
                'post_tag_label'       => 'Sélectionner l\'étiquette:',
                'none_option'          => 'Aucun',
                'standard_post'        => 'Article Standard',
                'all_languages'        => 'Toutes les langues (Traductions)',
                // AJAX & Notifications
                'confirm_publish'      => 'Publier cet article sur votre site ?',
                'confirm_draft'        => 'Enregistrer cet article comme brouillon ?',
                'confirm_delete'       => 'Voulez-vous vraiment supprimer cette publication ? Elle sera retirée du panneau de curation et pourra être récupérée lors du prochain scan.',
                'confirm_delete_all'   => 'Voulez-vous vraiment supprimer toutes les publications en attente ? Cela videra votre file d\'attente.',
                'rewriting'            => 'Réécriture avec l\'IA...',
                'publishing'           => 'Publication...',
                'saving_draft'         => 'Enregistrement du brouillon...',
                'deleting'             => 'Suppression...',
                'deleting_all'         => 'Suppression de toutes les publications...',
                'fetching'             => 'Récupération des publications...',
                'success_publish'      => 'Article publié avec succès !',
                'success_draft'        => 'Article enregistré comme brouillon !',
                'success_rewrite'      => 'Texte réécrit avec succès !',
                'success_delete'       => 'Publication supprimée avec succès !',
                'success_delete_all'   => 'Toutes les publications en attente ont été supprimées !',
                'error_generic'        => 'Une erreur est survenue. Veuillez réessayer.',
                'all_processed'        => 'Toutes les publications ont été traitées !',
                'all_processed_desc'   => 'Excellent travail ! Revenez plus tard pour du nouveau contenu.',
                'post_empty'           => 'Le contenu de l\'article ne peut pas être vide.',
                'sideload_err'         => 'Erreur lors du téléchargement de l\'image',
                'save_settings'        => 'Enregistrer les paramètres',
                'manual_actions'       => 'Actions Manuelles',
                'manual_fetch_desc'    => 'Déclencher une récupération manuelle sans attendre la prochaine exécution planifiée du cron.',
                'next_sched'           => 'Prochaine récupération planifiée:',
                // Settings headers
                'tab_basic'            => 'Configuration d\'Apify et des Pages (Configuration de Base)',
                'tab_ai'               => 'Configuration de Réécriture d\'IA (Configuration d\'IA)',
                'tab_cron'             => 'Configuration du CRON Externe (CRON)',
                'apify_section_title'  => 'Configuration d\'Apify et des Pages',
                'apify_section_desc'   => 'Entrez vos identifiants API Apify et les URLs/noms de pages Facebook à surveiller.',
                'apify_token_label'    => 'Jeton API Apify',
                'apify_token_desc'     => 'Votre jeton API Apify. Vous pouvez le trouver dans votre console Apify sous Settings -> Integrations -> API Tokens.',
                'fb_pages_label'       => 'URLs / Noms d\'utilisateur de pages Facebook',
                'fb_pages_desc'        => 'Liste séparée por des virgules d\'URLs de pages Facebook ou de noms d\'utilisateur.',
                'ai_section_title'     => 'Configuration de Réécriture d\'IA',
                'ai_section_desc'      => 'Configurez le fournisseur d\'IA pour la réécriture de texte facultative.',
                'ai_provider_label'    => 'Fournisseur d\'IA',
                'ai_key_label'         => 'Clé API d\'IA',
                'ai_key_desc'          => 'Votre clé API pour le fournisseur d\'IA sélectionné.',
                'ai_prompt_label'      => 'System Prompt (Instructions d\'IA)',
                'ai_prompt_desc'       => 'Instructions de base envoyées à l\'IA lorsque vous cliquez sur "Optimiser avec l\'IA".',
                'wp_native_ai'         => 'IA Native de WordPress 7',
                // CRON configuration labels
                'cron_section_title'   => 'Configuration du CRON Externe',
                'cron_section_desc'    => 'Configurez la modalité CRON externe pour traiter automatiquement les publications en attente via une requête URL.',
                'cron_enable_label'    => 'Activer le CRON Externe',
                'cron_token_label'     => 'Jeton Secret',
                'cron_limit_label'     => 'Limite par Défaut',
                'cron_status_label'    => 'Statut par Défaut',
                'cron_url_label'       => 'URL de Déclenchement du CRON',
                'cron_url_desc'        => 'C\'est l\'URL que votre serveur ou service externe doit appeler pour déclencher le processus de curation automatique.',
                // Event custom CPT agenda fields
                'event_fields_title'   => 'Champs de l\'événement (Crocoblock)',
                'event_start_label'    => 'Date et heure de début',
                'event_end_label'      => 'Date et heure de fin',
                'event_location_label' => 'Lieu (Lugar)',
                'event_location_placeholder' => 'Entrez le lieu ou l\'adresse',
                'event_coords_label'   => 'Coordonnées (Coordenadas)',
                'event_coords_placeholder' => 'ex. 43.123,-8.456',
                'event_video_label'    => 'Vidéo de l\'événement',
                'categorias_agenda_label' => 'Catégories d\'agenda:',
                'concellos_eventos_label' => 'Concellos (Événements):',
                'multiselect_help'     => 'Maintenez Ctrl (Cmd sur Mac) pour en sélectionner plusieurs.',
            ),
        );
        $lang = strtolower( $lang );
        return $dict[ $lang ] ?? $dict['en'];
    }

    /**
     * Get all public non-hierarchical terms (tags) grouped by taxonomy.
     *
     * @return array
     */
    private function get_grouped_tags() {
        $taxonomies = get_taxonomies( array( 'public' => true, 'hierarchical' => false ), 'objects' );
        $grouped = array();
        foreach ( $taxonomies as $tax_name => $tax_obj ) {
            if ( in_array( $tax_name, array( 'post_format' ), true ) ) {
                continue;
            }
            $terms = get_terms( array(
                'taxonomy'   => $tax_name,
                'hide_empty' => false,
            ) );
            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                $grouped[ $tax_name ] = array(
                    'label' => $tax_obj->labels->name,
                    'terms' => $terms,
                );
            }
        }
        return $grouped;
    }

    /**
     * Initialize admin hooks.
     *
     * @return void
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'register_menus' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // AJAX handlers (logged-in users only).
        add_action( 'wp_ajax_content_curator_rewrite', array( $this, 'ajax_rewrite' ) );
        add_action( 'wp_ajax_content_curator_publish', array( $this, 'ajax_publish' ) );
        add_action( 'wp_ajax_content_curator_fetch_now', array( $this, 'ajax_fetch_now' ) );
        add_action( 'wp_ajax_content_curator_delete', array( $this, 'ajax_delete' ) );
        add_action( 'wp_ajax_content_curator_delete_all', array( $this, 'ajax_delete_all' ) );
        add_action( 'wp_ajax_content_curator_change_plugin_lang', array( $this, 'ajax_change_plugin_lang' ) );
    }

    // =========================================================================
    // MENU REGISTRATION
    // =========================================================================

    /**
     * Register the admin menu and submenu pages.
     *
     * @return void
     */
    public function register_menus() {
        // Top-level menu.
        add_menu_page(
            __( 'Content Curator', 'wp-content-curator' ),
            __( 'Content Curator', 'wp-content-curator' ),
            'edit_posts',
            'content-curator-dashboard',
            array( $this, 'render_dashboard_page' ),
            'dashicons-facebook',
            30
        );

        // Submenu: Dashboard (same as top-level).
        add_submenu_page(
            'content-curator-dashboard',
            __( 'Curation Dashboard', 'wp-content-curator' ),
            __( 'Dashboard', 'wp-content-curator' ),
            'edit_posts',
            'content-curator-dashboard',
            array( $this, 'render_dashboard_page' )
        );

        // Submenu: Settings.
        add_submenu_page(
            'content-curator-dashboard',
            __( 'Content Curator Settings', 'wp-content-curator' ),
            __( 'Settings', 'wp-content-curator' ),
            'manage_options',
            'content-curator-settings',
            array( $this, 'render_settings_page' )
        );
    }

    // =========================================================================
    // SETTINGS REGISTRATION (WordPress Settings API)
    // =========================================================================

    /**
     * Register plugin settings with the WordPress Settings API.
     *
     * @return void
     */
    public function register_settings() {
        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d = self::get_dictionary( $plugin_lang );

        $wpml_active = false;
        $wpml_languages = array();
        if ( has_filter( 'wpml_active_languages' ) ) {
            $wpml_languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );
            if ( is_array( $wpml_languages ) && ! empty( $wpml_languages ) ) {
                $wpml_active = true;
            }
        }
        $default_curated = $wpml_active ? array_keys( $wpml_languages ) : array( 'en', 'es', 'fr' );

        // Register each option.
        register_setting( 'content_curator_settings_group', 'content_curator_plugin_language', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'en',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_curated_languages', array(
            'type'              => 'array',
            'sanitize_callback' => array( $this, 'sanitize_array' ),
            'default'           => $default_curated,
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_default_post_type', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'post',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_default_post_tag', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_apify_token', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_page_ids', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_ai_provider', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'openai',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_ai_api_key', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_ai_prompt', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'default'           => '',
        ) );

        // Section: Facebook API.
        add_settings_section(
            'content_curator_fb_section',
            $d['apify_section_title'],
            function () use ( $d ) {
                echo '<p>' . esc_html( $d['apify_section_desc'] ) . '</p>';
            },
            'content-curator-settings'
        );

        add_settings_field(
            'content_curator_plugin_language',
            $d['language_label'],
            array( $this, 'render_field_plugin_language' ),
            'content-curator-settings',
            'content_curator_fb_section'
        );

        add_settings_field(
            'content_curator_curated_languages',
            __( 'Curated Languages', 'wp-content-curator' ),
            array( $this, 'render_field_curated_languages' ),
            'content-curator-settings',
            'content_curator_fb_section'
        );

        add_settings_field(
            'content_curator_default_post_type',
            __( 'Default Post Type', 'wp-content-curator' ),
            array( $this, 'render_field_default_post_type' ),
            'content-curator-settings',
            'content_curator_fb_section'
        );

        add_settings_field(
            'content_curator_default_post_tag',
            __( 'Default Post Tag', 'wp-content-curator' ),
            array( $this, 'render_field_default_post_tag' ),
            'content-curator-settings',
            'content_curator_fb_section'
        );

        add_settings_field(
            'content_curator_apify_token',
            $d['apify_token_label'],
            array( $this, 'render_field_apify_token' ),
            'content-curator-settings',
            'content_curator_fb_section'
        );

        add_settings_field(
            'content_curator_page_ids',
            $d['fb_pages_label'],
            array( $this, 'render_field_page_ids' ),
            'content-curator-settings',
            'content_curator_fb_section'
        );

        // Section: AI Configuration.
        add_settings_section(
            'content_curator_ai_section',
            $d['ai_section_title'],
            function () use ( $d ) {
                echo '<p>' . esc_html( $d['ai_section_desc'] ) . '</p>';
            },
            'content-curator-settings'
        );

        add_settings_field(
            'content_curator_ai_provider',
            $d['ai_provider_label'],
            array( $this, 'render_field_ai_provider' ),
            'content-curator-settings',
            'content_curator_ai_section'
        );

        add_settings_field(
            'content_curator_ai_api_key',
            $d['ai_key_label'],
            array( $this, 'render_field_ai_api_key' ),
            'content-curator-settings',
            'content_curator_ai_section'
        );

        add_settings_field(
            'content_curator_ai_prompt',
            $d['ai_prompt_label'],
            array( $this, 'render_field_ai_prompt' ),
            'content-curator-settings',
            'content_curator_ai_section'
        );

        // Register CRON options.
        register_setting( 'content_curator_settings_group', 'content_curator_enable_external_cron', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '0',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_external_cron_token', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_external_cron_limit', array(
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'default'           => 5,
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_external_cron_status', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'draft',
        ) );

        // Section: CRON Configuration.
        add_settings_section(
            'content_curator_cron_section',
            $d['cron_section_title'],
            function () use ( $d ) {
                echo '<p>' . esc_html( $d['cron_section_desc'] ) . '</p>';
            },
            'content-curator-settings'
        );

        add_settings_field(
            'content_curator_enable_external_cron',
            $d['cron_enable_label'],
            array( $this, 'render_field_enable_external_cron' ),
            'content-curator-settings',
            'content_curator_cron_section'
        );

        add_settings_field(
            'content_curator_external_cron_token',
            $d['cron_token_label'],
            array( $this, 'render_field_external_cron_token' ),
            'content-curator-settings',
            'content_curator_cron_section'
        );

        add_settings_field(
            'content_curator_external_cron_limit',
            $d['cron_limit_label'],
            array( $this, 'render_field_external_cron_limit' ),
            'content-curator-settings',
            'content_curator_cron_section'
        );

        add_settings_field(
            'content_curator_external_cron_status',
            $d['cron_status_label'],
            array( $this, 'render_field_external_cron_status' ),
            'content-curator-settings',
            'content_curator_cron_section'
        );

        add_settings_field(
            'content_curator_external_cron_url',
            $d['cron_url_label'],
            array( $this, 'render_field_external_cron_url' ),
            'content-curator-settings',
            'content_curator_cron_section'
        );
    }

    // =========================================================================
    // SETTINGS FIELD RENDERERS
    // =========================================================================

    /**
     * Render the Plugin UI Language field.
     */
    public function render_field_plugin_language() {
        $value = get_option( 'content_curator_plugin_language', 'en' );
        ?>
        <select id="content_curator_plugin_language" name="content_curator_plugin_language">
            <option value="en" <?php selected( $value, 'en' ); ?>>English</option>
            <option value="es" <?php selected( $value, 'es' ); ?>>Español</option>
            <option value="fr" <?php selected( $value, 'fr' ); ?>>Français</option>
        </select>
        <p class="description"><?php esc_html_e( 'Choose the interface language for the curation dashboard and settings.', 'wp-content-curator' ); ?></p>
        <?php
    }

    /**
     * Sanitize array values from settings page checkboxes.
     */
    public function sanitize_array( $value ) {
        if ( ! is_array( $value ) ) {
            return array();
        }
        return array_map( 'sanitize_text_field', $value );
    }

    /**
     * Render Curated Languages list of checkboxes.
     */
    public function render_field_curated_languages() {
        $value = get_option( 'content_curator_curated_languages', array() );
        if ( ! is_array( $value ) ) {
            $value = array();
        }

        $wpml_languages = array();
        if ( has_filter( 'wpml_active_languages' ) ) {
            $wpml_languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );
        }

        $available_languages = array();
        if ( ! empty( $wpml_languages ) && is_array( $wpml_languages ) ) {
            foreach ( $wpml_languages as $code => $info ) {
                $available_languages[ $code ] = $info['display_name'] ?? $info['native_name'] ?? $code;
            }
        } else {
            $available_languages = array(
                'en' => 'English',
                'es' => 'Español',
                'fr' => 'Français',
                'gl' => 'Galego',
            );
        }

        if ( empty( $value ) ) {
            $value = array_keys( $available_languages );
        }

        foreach ( $available_languages as $code => $name ) {
            $checked = in_array( $code, $value, true ) ? 'checked' : '';
            printf(
                '<label style="margin-right: 20px; display: inline-block;"><input type="checkbox" name="content_curator_curated_languages[]" value="%s" %s /> %s</label>',
                esc_attr( $code ),
                $checked,
                esc_html( $name )
            );
        }
        printf( '<p class="description">%s</p>', esc_html__( 'Select which languages will appear as tabs in the curation dashboard. A post will be generated for each checked language.', 'wp-content-curator' ) );
    }

    /**
     * Render Default Post Type select field.
     */
    public function render_field_default_post_type() {
        $value = get_option( 'content_curator_default_post_type', 'post' );
        $custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
        ?>
        <select id="content_curator_default_post_type" name="content_curator_default_post_type">
            <option value="post" <?php selected( $value, 'post' ); ?>><?php esc_html_e( 'Standard Post (post)', 'wp-content-curator' ); ?></option>
            <option value="page" <?php selected( $value, 'page' ); ?>><?php esc_html_e( 'Page (page)', 'wp-content-curator' ); ?></option>
            <?php foreach ( $custom_post_types as $pt_name => $pt_obj ) : ?>
                <option value="<?php echo esc_attr( $pt_name ); ?>" <?php selected( $value, $pt_name ); ?>>
                    <?php echo esc_html( $pt_obj->labels->singular_name . ' (' . $pt_name . ')' ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e( 'Choose the default post type pre-selected on curation cards.', 'wp-content-curator' ); ?></p>
        <?php
    }

    /**
     * Render Default Post Tag select field.
     */
    public function render_field_default_post_tag() {
        $value = get_option( 'content_curator_default_post_tag', '' );
        $grouped_tags = $this->get_grouped_tags();
        ?>
        <select id="content_curator_default_post_tag" name="content_curator_default_post_tag">
            <option value=""><?php esc_html_e( 'None', 'wp-content-curator' ); ?></option>
            <?php foreach ( $grouped_tags as $tax_name => $tax_data ) : ?>
                <optgroup label="<?php echo esc_attr( $tax_data['label'] ); ?>">
                    <?php foreach ( $tax_data['terms'] as $term ) : ?>
                        <option value="<?php echo esc_attr( $tax_name . ':' . $term->term_id ); ?>" <?php selected( $value, $tax_name . ':' . $term->term_id ); ?>>
                            <?php echo esc_html( $term->name ); ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e( 'Choose the default tag pre-selected on curation cards.', 'wp-content-curator' ); ?></p>
        <?php
    }

    /**
     * Render the Apify API Token field.
     */
    public function render_field_apify_token() {
        $value = get_option( 'content_curator_apify_token', '' );
        printf(
            '<input type="password" id="content_curator_apify_token" name="content_curator_apify_token" value="%s" class="regular-text" autocomplete="off" />
            <p class="description">%s <a href="https://console.apify.com/account/integrations" target="_blank">%s</a>.</p>',
            esc_attr( $value ),
            esc_html__( 'Your Apify API Token. You can find it in your Apify Console under Settings ? Integrations ?', 'wp-content-curator' ),
            esc_html__( 'API Tokens', 'wp-content-curator' )
        );
    }

    /**
     * Render the Page IDs/URLs field.
     */
    public function render_field_page_ids() {
        $value = get_option( 'content_curator_page_ids', '' );
        printf(
            '<input type="text" id="content_curator_page_ids" name="content_curator_page_ids" value="%s" class="large-text" placeholder="https://www.facebook.com/nike, techcrunch" />
            <p class="description">%s</p>',
            esc_attr( $value ),
            esc_html__( 'Comma-separated list of Facebook Page URLs or usernames (e.g. "https://www.facebook.com/nike" or simply "nike").', 'wp-content-curator' )
        );
    }

    /**
     * Render the AI Provider select field.
     */
    public function render_field_ai_provider() {
        $value     = get_option( 'content_curator_ai_provider', 'openai' );
        $has_wp_ai = function_exists( 'wp_ai_client_prompt' );
        ?>
        <select id="content_curator_ai_provider" name="content_curator_ai_provider">
            <option value="openai" <?php selected( $value, 'openai' ); ?>>OpenAI (gpt-4o-mini)</option>
            <option value="anthropic" <?php selected( $value, 'anthropic' ); ?>>Anthropic (claude-3-haiku)</option>
            <option value="gemini" <?php selected( $value, 'gemini' ); ?>>Google Gemini (gemini-1.5-flash) - Gratis</option>
            <?php if ( $has_wp_ai ) : ?>
                <option value="wordpress_ai" <?php selected( $value, 'wordpress_ai' ); ?>><?php esc_html_e( 'WordPress 7 Native AI', 'wp-content-curator' ); ?></option>
            <?php endif; ?>
        </select>
        <?php
    }

    /**
     * Render the AI API Key field.
     */
    public function render_field_ai_api_key() {
        $value = get_option( 'content_curator_ai_api_key', '' );
        printf(
            '<input type="password" id="content_curator_ai_api_key" name="content_curator_ai_api_key" value="%s" class="regular-text" autocomplete="off" />
            <p class="description">%s</p>',
            esc_attr( $value ),
            esc_html__( 'Your API key for the selected AI provider.', 'wp-content-curator' )
        );
    }

    /**
     * Render the AI System Prompt field.
     */
    public function render_field_ai_prompt() {
        $default_prompt = 'You act as a professional blog writer optimized for SEO. Your goal is to completely rewrite the text provided to you. You must transform it into a short, structured, attractive article with a clear headline at the beginning preceded by an H2 tag. Do not use social media hashtags under any circumstances. Keep the original meaning but completely change the wording to avoid duplicate content penalties.';
        $value = get_option( 'content_curator_ai_prompt', '' );
        if ( empty( $value ) ) {
            $value = $default_prompt;
        }
        printf(
            '<textarea id="content_curator_ai_prompt" name="content_curator_ai_prompt" rows="6" class="large-text">%s</textarea>
            <p class="description">%s</p>',
            esc_textarea( $value ),
            esc_html__( 'Instrucciones base que se enviarán a la IA cada vez que pulses en "Optimizar con IA".', 'wp-content-curator' )
        );
    }

    /**
     * Render the enable external CRON checkbox.
     */
    public function render_field_enable_external_cron() {
        $value = get_option( 'content_curator_enable_external_cron', '0' );
        ?>
        <input type="checkbox" id="content_curator_enable_external_cron" name="content_curator_enable_external_cron" value="1" <?php checked( $value, '1' ); ?> />
        <span class="description"><?php esc_html_e( 'Enable processing pending posts automatically via an external HTTP GET request.', 'wp-content-curator' ); ?></span>
        <?php
    }

    /**
     * Render the external CRON secret token field.
     */
    public function render_field_external_cron_token() {
        $value = get_option( 'content_curator_external_cron_token', '' );
        if ( empty( $value ) ) {
            $value = wp_generate_password( 24, false, false );
            update_option( 'content_curator_external_cron_token', $value );
        }
        printf(
            '<input type="text" id="content_curator_external_cron_token" name="content_curator_external_cron_token" value="%s" class="regular-text" />
            <p class="description">%s</p>',
            esc_attr( $value ),
            esc_html__( 'Secret token to authenticate CRON requests. Auto-generated on page load if left empty.', 'wp-content-curator' )
        );
    }

    /**
     * Render the external CRON limit field.
     */
    public function render_field_external_cron_limit() {
        $value = get_option( 'content_curator_external_cron_limit', 5 );
        printf(
            '<input type="number" id="content_curator_external_cron_limit" name="content_curator_external_cron_limit" value="%d" class="small-text" min="1" step="1" />
            <p class="description">%s</p>',
            absint( $value ),
            esc_html__( 'Number of pending posts to process per run by default.', 'wp-content-curator' )
        );
    }

    /**
     * Render the external CRON default post status field.
     */
    public function render_field_external_cron_status() {
        $value = get_option( 'content_curator_external_cron_status', 'draft' );
        ?>
        <select id="content_curator_external_cron_status" name="content_curator_external_cron_status">
            <option value="draft" <?php selected( $value, 'draft' ); ?>><?php esc_html_e( 'Save as Draft', 'wp-content-curator' ); ?></option>
            <option value="publish" <?php selected( $value, 'publish' ); ?>><?php esc_html_e( 'Publish directly', 'wp-content-curator' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Choose whether to save processed posts as drafts or publish them directly.', 'wp-content-curator' ); ?></p>
        <?php
    }

    /**
     * Render the copyable external CRON URL trigger description.
     */
    public function render_field_external_cron_url() {
        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d = self::get_dictionary( $plugin_lang );
        $token = get_option( 'content_curator_external_cron_token', '' );
        if ( empty( $token ) ) {
            $token = wp_generate_password( 24, false, false );
            update_option( 'content_curator_external_cron_token', $token );
        }
        $limit = get_option( 'content_curator_external_cron_limit', 5 );
        $status = get_option( 'content_curator_external_cron_status', 'draft' );

        $cron_url = add_query_arg(
            array(
                'content_curator_cron_trigger' => 1,
                'token'                        => $token,
                'limit'                        => $limit,
                'status'                       => $status,
            ),
            home_url( '/' )
        );
        printf(
            '<input type="text" class="large-text" value="%s" readonly onclick="this.select();" />
            <p class="description">%s</p>',
            esc_url( $cron_url ),
            esc_html( $d['cron_url_desc'] )
        );
    }

    // =========================================================================
    // ASSET ENQUEUEING
    // =========================================================================

    /**
     * Enqueue CSS and JS assets only on plugin admin pages.
     *
     * @param string $hook_suffix The current admin page hook suffix.
     * @return void
     */
    public function enqueue_assets( $hook_suffix ) {
        // Only load on our plugin pages.
        $plugin_pages = array(
            'toplevel_page_content-curator-dashboard',
            'content-curator_page_content-curator-settings',
        );

        if ( ! in_array( $hook_suffix, $plugin_pages, true ) ) {
            return;
        }

        wp_enqueue_style(
            'content-curator-admin-css',
            WP_CONTENT_CURATOR_URL . 'assets/css/admin-style.css',
            array(),
            WP_CONTENT_CURATOR_VERSION
        );

        wp_enqueue_script(
            'content-curator-admin-js',
            WP_CONTENT_CURATOR_URL . 'assets/js/admin-script.js',
            array( 'jquery' ),
            WP_CONTENT_CURATOR_VERSION,
            true
        );

        wp_localize_script(
            'content-curator-admin-js',
            'contentCuratorData',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'content_curator_nonce' ),
                'strings'  => array(
                    'confirm_publish'    => __( 'Publish this post to your site?', 'wp-content-curator' ),
                    'confirm_draft'      => __( 'Save this post as a draft?', 'wp-content-curator' ),
                    'confirm_delete'     => __( 'Are you sure you want to delete this post? It will be removed from the curation panel and can be fetched again in the next scrape.', 'wp-content-curator' ),
                    'confirm_delete_all' => __( 'Are you sure you want to delete all pending posts? This will empty your curation queue. They can be fetched again in the next scrape.', 'wp-content-curator' ),
                    'rewriting'          => __( 'Rewriting with AI...', 'wp-content-curator' ),
                    'publishing'         => __( 'Publishing...', 'wp-content-curator' ),
                    'saving_draft'       => __( 'Saving draft...', 'wp-content-curator' ),
                    'deleting'           => __( 'Deleting...', 'wp-content-curator' ),
                    'deleting_all'       => __( 'Deleting all posts...', 'wp-content-curator' ),
                    'fetching'           => __( 'Fetching posts...', 'wp-content-curator' ),
                    'success_publish'    => __( 'Post published successfully!', 'wp-content-curator' ),
                    'success_draft'      => __( 'Post saved as draft!', 'wp-content-curator' ),
                    'success_rewrite'    => __( 'Text rewritten successfully!', 'wp-content-curator' ),
                    'success_delete'     => __( 'Post deleted successfully!', 'wp-content-curator' ),
                    'success_delete_all' => __( 'All pending posts deleted successfully!', 'wp-content-curator' ),
                    'error_generic'      => __( 'An error occurred. Please try again.', 'wp-content-curator' ),
                ),
            )
        );
    }

    // =========================================================================
    // SETTINGS PAGE RENDER
    // =========================================================================
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'wp-content-curator' ) );
        }
        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d = self::get_dictionary( $plugin_lang );
        ?>
        <div class="wrap content-curator-wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'content_curator_settings_group' ); ?>
                
                <h2 class="nav-tab-wrapper content-curator-settings-tabs" style="margin-bottom: 20px;">
                    <a href="#tab-basic" class="nav-tab nav-tab-active" data-tab="basic"><?php echo esc_html( $d['tab_basic'] ); ?></a>
                    <a href="#tab-ai" class="nav-tab" data-tab="ai"><?php echo esc_html( $d['tab_ai'] ); ?></a>
                    <a href="#tab-cron" class="nav-tab" data-tab="cron"><?php echo esc_html( $d['tab_cron'] ); ?></a>
                </h2>

                <div class="content-curator-tab-contents">
                    <!-- Tab 1: Basic Configuration -->
                    <div id="tab-basic" class="settings-tab-content">
                        <p class="description" style="margin-bottom: 15px; font-size: 13px;"><?php echo esc_html( $d['apify_section_desc'] ); ?></p>
                        <table class="form-table" role="presentation">
                            <?php do_settings_fields( 'content-curator-settings', 'content_curator_fb_section' ); ?>
                        </table>
                    </div>

                    <!-- Tab 2: AI Configuration -->
                    <div id="tab-ai" class="settings-tab-content" style="display: none;">
                        <p class="description" style="margin-bottom: 15px; font-size: 13px;"><?php echo esc_html( $d['ai_section_desc'] ); ?></p>
                        <table class="form-table" role="presentation">
                            <?php do_settings_fields( 'content-curator-settings', 'content_curator_ai_section' ); ?>
                        </table>
                    </div>

                    <!-- Tab 3: CRON Configuration -->
                    <div id="tab-cron" class="settings-tab-content" style="display: none;">
                        <p class="description" style="margin-bottom: 15px; font-size: 13px;"><?php echo esc_html( $d['cron_section_desc'] ); ?></p>
                        <table class="form-table" role="presentation">
                            <?php do_settings_fields( 'content-curator-settings', 'content_curator_cron_section' ); ?>
                        </table>
                    </div>
                </div>

                <?php submit_button( __( 'Save Settings', 'wp-content-curator' ) ); ?>
            </form>

            <hr />

            <h2><?php esc_html_e( 'Manual Actions', 'wp-content-curator' ); ?></h2>
            <p class="description"><?php esc_html_e( 'Trigger a manual fetch without waiting for the next scheduled cron run.', 'wp-content-curator' ); ?></p>
            <button type="button" id="content-curator-fetch-now" class="button button-secondary">
                <span class="dashicons dashicons-download" style="vertical-align: middle; margin-right: 4px;"></span>
                <?php esc_html_e( 'Fetch Now', 'wp-content-curator' ); ?>
            </button>
            <span id="content-curator-fetch-status" class="content-curator-inline-status"></span>

            <?php
            // Show next scheduled run.
            $next = wp_next_scheduled( content_curator_Cron::CRON_HOOK );
            if ( $next ) {
                printf(
                    '<p class="description" style="margin-top: 10px;">%s <strong>%s</strong></p>',
                    esc_html__( 'Next scheduled fetch:', 'wp-content-curator' ),
                    esc_html( wp_date( 'Y-m-d H:i:s', $next ) )
                );
            }
            ?>
        </div>
        <?php
    }

    // =========================================================================
    // DASHBOARD (CURATION PANEL) RENDER
    // =========================================================================

    /**
     * Render the main curation dashboard page.
     *
     * @return void
     */
    public function render_dashboard_page() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'wp-content-curator' ) );
        }

        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d = self::get_dictionary( $plugin_lang );

        $custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
        $grouped_tags = $this->get_grouped_tags();

        $agenda_cats = get_terms( array(
            'taxonomy'   => 'categorias-agenda',
            'hide_empty' => false,
        ) );
        if ( is_wp_error( $agenda_cats ) ) {
            $agenda_cats = array();
        }

        $concellos_evs = get_terms( array(
            'taxonomy'   => 'concellos-eventos',
            'hide_empty' => false,
        ) );
        if ( is_wp_error( $concellos_evs ) ) {
            $concellos_evs = array();
        }

        $current_lang = 'en';
        $wpml_active = false;
        $wpml_languages = array();
        if ( has_filter( 'wpml_current_language' ) ) {
            $current_lang = apply_filters( 'wpml_current_language', null );
        }
        if ( has_filter( 'wpml_active_languages' ) ) {
            $wpml_languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );
            if ( is_array( $wpml_languages ) && ! empty( $wpml_languages ) ) {
                $wpml_active = true;
            }
        }

        $default_curated = $wpml_active ? array_keys( $wpml_languages ) : array( 'en', 'es', 'fr' );
        $curated_langs = get_option( 'content_curator_curated_languages', $default_curated );
        if ( ! is_array( $curated_langs ) ) {
            $curated_langs = array();
        }

        $default_type = get_option( 'content_curator_default_post_type', 'post' );
        $default_tag  = get_option( 'content_curator_default_post_tag', '' );

        $lang_names = array(
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'gl' => 'Galego',
        );
        if ( $wpml_active ) {
            foreach ( $wpml_languages as $code => $info ) {
                $lang_names[ $code ] = $info['display_name'] ?? $info['native_name'] ?? $code;
            }
        }

        // Get the current time filter from query params.
        $filter  = isset( $_GET['hours'] ) ? sanitize_text_field( wp_unslash( $_GET['hours'] ) ) : 'all';
        $allowed = array( '24h', '48h', '7d', 'all' );
        if ( ! in_array( $filter, $allowed, true ) ) {
            $filter = 'all';
        }

        // Get the current site/page filter from query params.
        $site_filter = isset( $_GET['site'] ) ? sanitize_text_field( wp_unslash( $_GET['site'] ) ) : 'all';

        // Get start and end dates from query params.
        $start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
        $end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';

        // Pagination setup.
        $posts_per_page = 12;
        $paged          = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
        $offset         = ( $paged - 1 ) * $posts_per_page;

        // Fetch unique site list.
        $sites = Content_Curator_DB::get_unique_sites();

        // Fetch total count for pagination.
        $total_posts = Content_Curator_DB::get_pending_posts_count( $filter, $site_filter, $start_date, $end_date );

        // Fetch pending posts for the current page.
        $posts = Content_Curator_DB::get_pending_posts( $filter, $site_filter, $posts_per_page, $offset, $start_date, $end_date );
        ?>
        <div class="wrap content-curator-wrap">
            <div class="content-curator-dashboard-banner">
                <div class="banner-overlay"></div>
                <div class="banner-content">
                    <img src="<?php echo esc_url( WP_CONTENT_CURATOR_URL . 'assets/images/icon.png' ); ?>" alt="" class="content-curator-banner-icon" />
                    <h1><?php echo esc_html( $d['dashboard_title'] ); ?></h1>
                    <p class="banner-desc"><?php echo esc_html( $d['dashboard_desc'] ); ?></p>
                </div>
            </div>

            <!-- Toolbar Form: Filter + Stats -->
            <form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="content-curator-toolbar-form">
                <input type="hidden" name="page" value="content-curator-dashboard" />

                <div class="content-curator-toolbar">
                    <div class="content-curator-toolbar-left">
                        <div class="toolbar-item">
                            <label for="content-curator-filter"><?php echo esc_html( $d['time'] ); ?></label>
                            <select id="content-curator-filter" name="hours" onchange="this.form.submit();">
                                <option value="all" <?php selected( $filter, 'all' ); ?>><?php echo esc_html( $d['all_pending'] ); ?></option>
                                <option value="24h" <?php selected( $filter, '24h' ); ?>><?php echo esc_html( $d['last_24'] ); ?></option>
                                <option value="48h" <?php selected( $filter, '48h' ); ?>><?php echo esc_html( $d['last_48'] ); ?></option>
                                <option value="7d"  <?php selected( $filter, '7d' ); ?>><?php echo esc_html( $d['last_7d'] ); ?></option>
                            </select>
                        </div>

                        <div class="toolbar-item">
                            <label for="content-curator-site-filter"><?php echo esc_html( $d['site'] ); ?></label>
                            <select id="content-curator-site-filter" name="site" onchange="this.form.submit();">
                                <option value="all" <?php selected( $site_filter, 'all' ); ?>><?php echo esc_html( $d['todos'] ); ?></option>
                                <?php foreach ( $sites as $site_name ) : ?>
                                    <option value="<?php echo esc_attr( $site_name ); ?>" <?php selected( $site_filter, $site_name ); ?>><?php echo esc_html( $site_name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="toolbar-item">
                            <label for="content-curator-start-date"><?php echo esc_html( $d['from'] ); ?></label>
                            <input type="date" id="content-curator-start-date" name="start_date" value="<?php echo esc_attr( $start_date ); ?>" onchange="this.form.submit();" />
                        </div>

                        <div class="toolbar-item">
                            <label for="content-curator-end-date"><?php echo esc_html( $d['to'] ); ?></label>
                            <input type="date" id="content-curator-end-date" name="end_date" value="<?php echo esc_attr( $end_date ); ?>" onchange="this.form.submit();" />
                        </div>

                        <div class="toolbar-item">
                            <label for="content-curator-plugin-lang-select"><?php echo esc_html( $d['language_label'] ); ?></label>
                            <select id="content-curator-plugin-lang-select" name="plugin_lang" class="content-curator-lang-switcher">
                                <option value="en" <?php selected( $plugin_lang, 'en' ); ?>>English</option>
                                <option value="es" <?php selected( $plugin_lang, 'es' ); ?>>Español</option>
                                <option value="fr" <?php selected( $plugin_lang, 'fr' ); ?>>Français</option>
                            </select>
                        </div>

                        <?php if ( ! empty( $start_date ) || ! empty( $end_date ) || 'all' !== $filter || 'all' !== $site_filter ) : ?>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=content-curator-dashboard' ) ); ?>" class="button button-secondary clear-filters-btn" style="margin-left: 10px;">
                                <?php echo esc_html( $d['clear_filters'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="content-curator-toolbar-right" style="display: flex; align-items: center; gap: 10px;">
                        <button type="button" id="content-curator-fetch-now" class="button button-secondary" style="display: inline-flex; align-items: center; gap: 4px;">
                            <span class="dashicons dashicons-download" style="vertical-align: middle;"></span>
                            <?php echo esc_html( $d['fetch_now'] ); ?>
                        </button>
                        <?php if ( $total_posts > 0 ) : ?>
                            <button type="button" id="content-curator-delete-all" class="button btn-delete-all">
                                <span class="dashicons dashicons-trash" style="vertical-align: middle;"></span>
                                <?php echo esc_html( $d['delete_all'] ); ?>
                            </button>
                        <?php endif; ?>
                        <span id="content-curator-fetch-status" class="content-curator-inline-status"></span>

                        <span class="content-curator-count">
                            <?php
                            $count_string = $total_posts === 1 ? $d['pending_posts'] : $d['pending_posts_plural'];
                            printf( '%d %s', $total_posts, esc_html( $count_string ) );
                            ?>
                        </span>
                    </div>
                </div>
            </form>

            <!-- Notification area -->
            <div id="content-curator-notices" class="content-curator-notices" style="display: none;"></div>

            <?php if ( empty( $posts ) ) : ?>
                <div class="content-curator-empty">
                    <span class="dashicons dashicons-clipboard" style="font-size: 48px; width: 48px; height: 48px; color: #c3c4c7;"></span>
                    <h2><?php echo esc_html( $d['no_pending'] ); ?></h2>
                    <p><?php echo esc_html( $d['no_pending_desc'] ); ?></p>
                </div>
            <?php else : ?>
                <!-- Card Grid -->
                <div class="content-curator-grid">
                    <?php foreach ( $posts as $post ) : ?>
                        <div class="content-curator-card" data-post-id="<?php echo esc_attr( $post->id ); ?>" id="card-<?php echo esc_attr( $post->id ); ?>">

                            <!-- Loading overlay -->
                            <div class="card-loading" style="display: none;">
                                <div class="card-spinner"></div>
                                <span class="card-loading-text"></span>
                            </div>

                            <!-- Card Header -->
                            <div class="card-header">
                                <span class="card-source">
                                    <span class="dashicons dashicons-facebook"></span>
                                    <?php echo esc_html( $post->page_name ); ?>
                                </span>
                                <span class="card-date">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <?php echo esc_html( wp_date( 'M j, Y – H:i', strtotime( $post->fb_created_at ) ) ); ?>
                                </span>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <?php 
                                $images = array();
                                if ( ! empty( $post->image_url ) ) {
                                    $decoded = json_decode( $post->image_url, true );
                                    $images  = is_array( $decoded ) ? $decoded : array( $post->image_url );
                                }
                                $images = array_filter( $images );
                                ?>
                                <?php if ( ! empty( $images ) ) : ?>
                                    <div class="card-image-gallery-container">
                                        <div class="card-image-gallery">
                                            <?php foreach ( $images as $idx => $img_url ) : ?>
                                                <div class="card-gallery-image <?php echo $idx === 0 ? 'active' : ''; ?>">
                                                    <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $d['facebook_image'] ); ?>" loading="lazy" />
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php if ( count( $images ) > 1 ) : ?>
                                            <div class="gallery-controls">
                                                <button type="button" class="gallery-prev" onclick="changeGalleryImage(this, -1);">&lsaquo;</button>
                                                <span class="gallery-counter">1 / <?php echo count( $images ); ?></span>
                                                <button type="button" class="gallery-next" onclick="changeGalleryImage(this, 1);">&rsaquo;</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="card-content">
                                    <!-- Original text (read-only) -->
                                    <div class="card-original">
                                        <h4><?php echo esc_html( $d['original_text'] ); ?></h4>
                                        <div class="card-original-text"><?php echo esc_html( $post->original_text ); ?></div>
                                    </div>

                                    <!-- Editable textarea with language tabs -->
                                    <div class="card-editor">
                                        <h4><?php echo esc_html( $d['editor'] ); ?></h4>
                                        <?php if ( count( $curated_langs ) > 1 ) : ?>
                                            <div class="editor-tabs" data-post-id="<?php echo esc_attr( $post->id ); ?>">
                                                <?php $first = true; foreach ( $curated_langs as $lang_code ) : ?>
                                                    <button type="button"
                                                        class="editor-tab-btn <?php echo $first ? 'active' : ''; ?>"
                                                        data-lang="<?php echo esc_attr( $lang_code ); ?>"
                                                        data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                                    >
                                                        <?php echo esc_html( $lang_names[ $lang_code ] ?? strtoupper( $lang_code ) ); ?>
                                                    </button>
                                                <?php $first = false; endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="editor-tab-contents">
                                            <?php $first = true; foreach ( $curated_langs as $lang_code ) : ?>
                                                <textarea
                                                    class="content-curator-textarea <?php echo $first ? 'active' : ''; ?>"
                                                    data-lang="<?php echo esc_attr( $lang_code ); ?>"
                                                    data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                                    rows="8"
                                                    style="<?php echo $first ? '' : 'display: none;'; ?>"
                                                ><?php echo esc_textarea( $post->original_text ); ?></textarea>
                                            <?php $first = false; endforeach; ?>
                                        </div>

                                        <div class="card-editor-actions" style="margin-top: 10px;">
                                            <button type="button"
                                                class="button btn-ai"
                                                data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                                title="<?php echo esc_attr( $d['optimize_ai'] ); ?>">
                                                <span class="dashicons dashicons-superhero-alt"></span>
                                                <?php echo esc_html( $d['optimize_ai'] ); ?>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Meta selectors: Post Type, Taxonomy Tag -->
                                    <div class="card-meta-selects">
                                        <div class="meta-select-item">
                                            <label><?php echo esc_html( $d['post_type_label'] ); ?></label>
                                            <select class="select-post-type" data-post-id="<?php echo esc_attr( $post->id ); ?>">
                                                <option value="post" <?php selected( $default_type, 'post' ); ?>><?php echo esc_html( $d['standard_post'] ); ?></option>
                                                <option value="page" <?php selected( $default_type, 'page' ); ?>><?php esc_html_e( 'Page', 'wp-content-curator' ); ?></option>
                                                <?php foreach ( $custom_post_types as $pt_name => $pt_obj ) : ?>
                                                    <option value="<?php echo esc_attr( $pt_name ); ?>" <?php selected( $default_type, $pt_name ); ?>><?php echo esc_html( $pt_obj->labels->singular_name ); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="meta-select-item">
                                            <label><?php echo esc_html( $d['post_tag_label'] ); ?></label>
                                            <select class="select-post-tag" data-post-id="<?php echo esc_attr( $post->id ); ?>">
                                                <option value=""><?php echo esc_html( $d['none_option'] ); ?></option>
                                                <?php foreach ( $grouped_tags as $tax_name => $tax_data ) : ?>
                                                    <optgroup label="<?php echo esc_attr( $tax_data['label'] ); ?>">
                                                        <?php foreach ( $tax_data['terms'] as $term ) : ?>
                                                            <option value="<?php echo esc_attr( $tax_name . ':' . $term->term_id ); ?>" <?php selected( $default_tag, $tax_name . ':' . $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Event Fields Section (for CPT agenda) -->
                                    <div class="agenda-only-fields" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed var(--cc-border);">
                                        <h4 style="margin-bottom: 12px; display: flex; align-items: center; gap: 6px; color: var(--cc-primary); font-size: 13px; font-weight: 600; text-transform: uppercase;">
                                            <span class="dashicons dashicons-calendar-alt"></span>
                                            <?php echo esc_html( $d['event_fields_title'] ); ?>
                                        </h4>
                                        
                                        <div class="meta-fields-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                                            <div class="meta-field-item">
                                                <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['event_start_label'] ); ?></label>
                                                <input type="datetime-local" class="event-start-date" style="width: 100%; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm);" />
                                            </div>
                                            <div class="meta-field-item">
                                                <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['event_end_label'] ); ?></label>
                                                <input type="datetime-local" class="event-end-date" style="width: 100%; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm);" />
                                            </div>
                                        </div>
                                        
                                        <div class="meta-fields-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                                            <div class="meta-field-item">
                                                <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['event_location_label'] ); ?></label>
                                                <input type="text" class="event-location" placeholder="<?php echo esc_attr( $d['event_location_placeholder'] ); ?>" style="width: 100%; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm);" />
                                            </div>
                                            <div class="meta-field-item">
                                                <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['event_coords_label'] ); ?></label>
                                                <input type="text" class="event-coords" placeholder="<?php echo esc_attr( $d['event_coords_placeholder'] ); ?>" style="width: 100%; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm);" />
                                            </div>
                                        </div>

                                        <div class="meta-field-item" style="margin-bottom: 12px;">
                                            <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['event_video_label'] ); ?></label>
                                            <input type="text" class="event-video" placeholder="https://..." style="width: 100%; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm);" />
                                        </div>

                                        <!-- Event Taxonomies -->
                                        <div class="meta-fields-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 5px;">
                                            <div class="meta-field-item">
                                                <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['categorias_agenda_label'] ); ?></label>
                                                <select class="event-categories" multiple="multiple" style="width: 100%; height: 100px; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm); font-size: 12px;">
                                                    <?php foreach ( $agenda_cats as $cat ) : ?>
                                                        <option value="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span style="font-size: 9px; color: var(--cc-text-muted); line-height: 1.1; display: block; margin-top: 2px;"><?php echo esc_html( $d['multiselect_help'] ); ?></span>
                                            </div>
                                            <div class="meta-field-item">
                                                <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['concellos_eventos_label'] ); ?></label>
                                                <select class="event-concellos" multiple="multiple" style="width: 100%; height: 100px; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm); font-size: 12px;">
                                                    <?php foreach ( $concellos_evs as $concello ) : ?>
                                                        <option value="<?php echo esc_attr( $concello->term_id ); ?>"><?php echo esc_html( $concello->name ); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span style="font-size: 9px; color: var(--cc-text-muted); line-height: 1.1; display: block; margin-top: 2px;"><?php echo esc_html( $d['multiselect_help'] ); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Actions -->
                            <div class="card-actions">
                                <button type="button"
                                    class="button btn-draft"
                                    data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                    data-image-url="<?php echo esc_attr( $post->image_url ?? '' ); ?>"
                                    data-status="draft"
                                    title="<?php echo esc_attr( $d['save_draft'] ); ?>">
                                    <span class="dashicons dashicons-edit-page"></span>
                                    <?php echo esc_html( $d['save_draft'] ); ?>
                                </button>

                                <button type="button"
                                    class="button btn-publish"
                                    data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                    data-image-url="<?php echo esc_attr( $post->image_url ?? '' ); ?>"
                                    data-status="publish"
                                    title="<?php echo esc_attr( $d['publish'] ); ?>">
                                    <span class="dashicons dashicons-upload"></span>
                                    <?php echo esc_html( $d['publish'] ); ?>
                                </button>

                                <button type="button"
                                    class="button btn-delete"
                                    data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                    title="<?php echo esc_attr( $d['delete'] ); ?>">
                                    <span class="dashicons dashicons-trash"></span>
                                    <?php echo esc_html( $d['delete'] ); ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php
                $total_pages = ceil( $total_posts / $posts_per_page );
                if ( $total_pages > 1 ) :
                    ?>
                    <div class="content-curator-pagination">
                        <?php
                        echo paginate_links( array(
                            'base'      => add_query_arg( 'paged', '%#%' ),
                            'format'    => '',
                            'prev_text' => $d['prev'],
                            'next_text' => $d['next'],
                            'total'     => $total_pages,
                            'current'   => $paged,
                        ) );
                        ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    // =========================================================================
    // AJAX HANDLERS
    // =========================================================================

    /**
     * AJAX: Rewrite post text using AI.
     *
     * Expected POST params: post_id, text, nonce.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_rewrite() {
        // Verify nonce.
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }

        // Verify capability.
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        $text    = isset( $_POST['text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['text'] ) ) : '';

        if ( empty( $text ) ) {
            wp_send_json_error( array( 'message' => __( 'No text provided for rewriting.', 'wp-content-curator' ) ) );
        }

        $wpml_active = false;
        $wpml_languages = array();
        if ( has_filter( 'wpml_active_languages' ) ) {
            $wpml_languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );
            if ( is_array( $wpml_languages ) && ! empty( $wpml_languages ) ) {
                $wpml_active = true;
            }
        }
        $default_curated = $wpml_active ? array_keys( $wpml_languages ) : array( 'en', 'es', 'fr' );
        $curated_langs = get_option( 'content_curator_curated_languages', $default_curated );
        if ( ! is_array( $curated_langs ) ) {
            $curated_langs = array();
        }

        $translations = array();
        foreach ( $curated_langs as $lang_code ) {
            $result = content_curator_API::rewrite_text( $text, $lang_code );
            if ( is_wp_error( $result ) ) {
                wp_send_json_error( array( 'message' => sprintf( __( 'AI translation error for language %1$s: %2$s', 'wp-content-curator' ), $lang_code, $result->get_error_message() ) ) );
            }
            $translations[ $lang_code ] = $result;
        }

        wp_send_json_success( array(
            'translations' => $translations,
            'post_id'      => $post_id,
        ) );
    }

    /**
     * AJAX: Publish or save a curated post as a WordPress entry.
     *
     * Expected POST params: post_id, text, image_url, publish_status, nonce.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_publish() {
        // Verify nonce.
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }

        // Verify capability.
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $db_post_id     = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        $texts_raw      = isset( $_POST['texts'] ) ? wp_unslash( $_POST['texts'] ) : '';
        $texts          = json_decode( $texts_raw, true );
        $image_url_param = isset( $_POST['image_url'] ) ? wp_unslash( $_POST['image_url'] ) : '';
        $publish_status  = isset( $_POST['publish_status'] ) ? sanitize_text_field( wp_unslash( $_POST['publish_status'] ) ) : 'draft';
        $post_type      = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'post';
        $tag_value      = isset( $_POST['tag'] ) ? sanitize_text_field( wp_unslash( $_POST['tag'] ) ) : '';

        // Event custom meta fields
        $event_start_date = isset( $_POST['event_start_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_start_date'] ) ) : '';
        $event_end_date   = isset( $_POST['event_end_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_end_date'] ) ) : '';
        $event_location   = isset( $_POST['event_location'] ) ? sanitize_text_field( wp_unslash( $_POST['event_location'] ) ) : '';
        $event_coords     = isset( $_POST['event_coords'] ) ? sanitize_text_field( wp_unslash( $_POST['event_coords'] ) ) : '';
        $event_video      = isset( $_POST['event_video'] ) ? esc_url_raw( wp_unslash( $_POST['event_video'] ) ) : '';
        
        $event_cats_raw   = isset( $_POST['event_categories'] ) ? wp_unslash( $_POST['event_categories'] ) : '';
        $event_concellos_raw = isset( $_POST['event_concellos'] ) ? wp_unslash( $_POST['event_concellos'] ) : '';
        
        $event_cats       = ! empty( $event_cats_raw ) ? array_map( 'absint', explode( ',', $event_cats_raw ) ) : array();
        $event_concellos  = ! empty( $event_concellos_raw ) ? array_map( 'absint', explode( ',', $event_concellos_raw ) ) : array();

        // Validate publish status.
        if ( ! in_array( $publish_status, array( 'draft', 'publish' ), true ) ) {
            $publish_status = 'draft';
        }

        if ( ! is_array( $texts ) || empty( $texts ) ) {
            // Fallback to single text if texts is missing
            $fallback_text = isset( $_POST['text'] ) ? wp_kses_post( wp_unslash( $_POST['text'] ) ) : '';
            $texts = array( 'en' => $fallback_text );
        }

        // Check if WPML is active.
        $wpml_active = false;
        $wpml_languages = array();
        $default_lang = 'en';
        if ( has_filter( 'wpml_active_languages' ) ) {
            $wpml_languages = apply_filters( 'wpml_active_languages', null, 'skip_missing=0' );
            if ( is_array( $wpml_languages ) && ! empty( $wpml_languages ) ) {
                $wpml_active = true;
                $default_lang = apply_filters( 'wpml_default_language', null );
                if ( empty( $default_lang ) ) {
                    $default_lang = 'en';
                }
            }
        }

        // Determine the master language to create the first post.
        $source_lang = $default_lang;
        if ( ! isset( $texts[ $source_lang ] ) ) {
            $keys = array_keys( $texts );
            $source_lang = ! empty( $keys ) ? $keys[0] : 'en';
        }

        $master_text = $texts[ $source_lang ] ?? '';
        if ( empty( $master_text ) ) {
            wp_send_json_error( array( 'message' => __( 'Post content cannot be empty.', 'wp-content-curator' ) ) );
        }

        // Extract title and body for the master post.
        $lines = preg_split( '/\r\n|\r|\n/', $master_text, 2 );
        $title = wp_strip_all_tags( $lines[0] );
        $title = preg_replace( '/^<h2[^>]*>(.*?)<\/h2>$/i', '$1', $title );
        $title = trim( $title );
        $body  = isset( $lines[1] ) ? trim( $lines[1] ) : $master_text;

        // Step 1: Insert the master post.
        $new_post_id = wp_insert_post(
            array(
                'post_title'   => $title,
                'post_content' => $body,
                'post_status'  => $publish_status,
                'post_type'    => $post_type,
                'post_author'  => get_current_user_id(),
            ),
            true
        );

        if ( is_wp_error( $new_post_id ) ) {
            wp_send_json_error( array( 'message' => $new_post_id->get_error_message() ) );
        }

        // Apply event meta and taxonomies to master post
        if ( 'agenda' === $post_type ) {
            if ( ! empty( $event_cats ) ) {
                wp_set_object_terms( $new_post_id, $event_cats, 'categorias-agenda' );
            }
            if ( ! empty( $event_concellos ) ) {
                wp_set_object_terms( $new_post_id, $event_concellos, 'concellos-eventos' );
            }
            if ( ! empty( $event_start_date ) ) {
                $start_ts = strtotime( $event_start_date );
                if ( $start_ts !== false ) {
                    update_post_meta( $new_post_id, 'fecha-de-inicio', $start_ts );
                }
            }
            if ( ! empty( $event_end_date ) ) {
                $end_ts = strtotime( $event_end_date );
                if ( $end_ts !== false ) {
                    update_post_meta( $new_post_id, 'fecha-de-fin', $end_ts );
                }
            }
            update_post_meta( $new_post_id, 'Lugar', $event_location );
            update_post_meta( $new_post_id, 'coordenadas', $event_coords );
            update_post_meta( $new_post_id, 'video-del-evento', $event_video );
            update_post_meta( $new_post_id, 'descripcion-del-evento', $master_text );
        }

        // Step 1.1: Set tag on master post.
        if ( ! empty( $tag_value ) && strpos( $tag_value, ':' ) !== false ) {
            list( $taxonomy, $term_id ) = explode( ':', $tag_value, 2 );
            wp_set_object_terms( $new_post_id, array( absint( $term_id ) ), sanitize_key( $taxonomy ) );
        }

        // Step 1.2: Set language on master post in WPML.
        $trid = null;
        if ( $wpml_active && has_action( 'wpml_set_element_language_details' ) ) {
            do_action(
                'wpml_set_element_language_details',
                array(
                    'element_id'    => $new_post_id,
                    'element_type'  => 'post_' . $post_type,
                    'trid'          => null,
                    'language_code' => $source_lang,
                    'source_language_code' => null,
                )
            );
            $trid = apply_filters( 'wpml_element_trid', null, $new_post_id, 'post_' . $post_type );
        }

        // Parse image URLs.
        $image_urls = array();
        if ( ! empty( $image_url_param ) ) {
            if ( is_array( $image_url_param ) ) {
                $image_urls = array_map( 'esc_url_raw', $image_url_param );
            } else {
                $decoded = json_decode( $image_url_param, true );
                if ( is_array( $decoded ) ) {
                    $image_urls = array_map( 'esc_url_raw', $decoded );
                } else {
                    $image_urls = array( esc_url_raw( $image_url_param ) );
                }
            }
        }
        $image_urls = array_filter( $image_urls );

        // Step 2: Sideload all images to master post.
        $attachment_ids = array();
        foreach ( $image_urls as $url ) {
            $attachment_id = self::sideload_image( $url, $new_post_id );
            if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
                $attachment_ids[] = $attachment_id;
            } else {
                if ( is_wp_error( $attachment_id ) ) {
                    error_log( '[WP FB Curator] Image sideload error: ' . $attachment_id->get_error_message() );
                }
            }
        }

        // Step 3: Set featured image & gallery block on master post.
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

        if ( 'agenda' === $post_type && ! empty( $attachment_ids ) ) {
            update_post_meta( $new_post_id, 'galeria-del-evento', implode( ',', $attachment_ids ) );
        }

        // Step 4: Insert translation posts for all other curated languages.
        foreach ( $texts as $lang_code => $lang_text ) {
            if ( $lang_code === $source_lang ) {
                continue;
            }

            if ( empty( $lang_text ) ) {
                continue;
            }

            // Extract title and body for this language.
            $lang_lines = preg_split( '/\r\n|\r|\n/', $lang_text, 2 );
            $lang_title = wp_strip_all_tags( $lang_lines[0] );
            $lang_title = preg_replace( '/^<h2[^>]*>(.*?)<\/h2>$/i', '$1', $lang_title );
            $lang_title = trim( $lang_title );
            $lang_body  = isset( $lang_lines[1] ) ? trim( $lang_lines[1] ) : $lang_text;

            // Append same gallery HTML if multiple images.
            if ( ! empty( $attachment_ids ) && count( $attachment_ids ) > 1 ) {
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

            $translated_post_id = wp_insert_post(
                array(
                    'post_title'   => $lang_title,
                    'post_content' => $lang_body,
                    'post_status'  => $publish_status,
                    'post_type'    => $post_type,
                    'post_author'  => get_current_user_id(),
                ),
                true
            );

            if ( ! is_wp_error( $translated_post_id ) ) {
                // Set tag.
                if ( ! empty( $tag_value ) && strpos( $tag_value, ':' ) !== false ) {
                    list( $taxonomy, $term_id ) = explode( ':', $tag_value, 2 );
                    wp_set_object_terms( $translated_post_id, array( absint( $term_id ) ), sanitize_key( $taxonomy ) );
                }

                // Set thumbnail.
                if ( ! empty( $attachment_ids ) ) {
                    set_post_thumbnail( $translated_post_id, $attachment_ids[0] );
                }

                // Save event taxonomies and meta on translation post
                if ( 'agenda' === $post_type ) {
                    if ( ! empty( $event_cats ) ) {
                        wp_set_object_terms( $translated_post_id, $event_cats, 'categorias-agenda' );
                    }
                    if ( ! empty( $event_concellos ) ) {
                        wp_set_object_terms( $translated_post_id, $event_concellos, 'concellos-eventos' );
                    }
                    if ( ! empty( $event_start_date ) ) {
                        $start_ts = strtotime( $event_start_date );
                        if ( $start_ts !== false ) {
                            update_post_meta( $translated_post_id, 'fecha-de-inicio', $start_ts );
                        }
                    }
                    if ( ! empty( $event_end_date ) ) {
                        $end_ts = strtotime( $event_end_date );
                        if ( $end_ts !== false ) {
                            update_post_meta( $translated_post_id, 'fecha-de-fin', $end_ts );
                        }
                    }
                    update_post_meta( $translated_post_id, 'Lugar', $event_location );
                    update_post_meta( $translated_post_id, 'coordenadas', $event_coords );
                    update_post_meta( $translated_post_id, 'video-del-evento', $event_video );
                    update_post_meta( $translated_post_id, 'descripcion-del-evento', $lang_text );
                    if ( ! empty( $attachment_ids ) ) {
                        update_post_meta( $translated_post_id, 'galeria-del-evento', implode( ',', $attachment_ids ) );
                    }
                }

                // Link in WPML.
                if ( $wpml_active && has_action( 'wpml_set_element_language_details' ) ) {
                    do_action(
                        'wpml_set_element_language_details',
                        array(
                            'element_id'    => $translated_post_id,
                            'element_type'  => 'post_' . $post_type,
                            'trid'          => $trid,
                            'language_code' => $lang_code,
                            'source_language_code' => $source_lang,
                        )
                    );
                }
            }
        }

        // Step 4: Mark the curated post as processed.
        if ( $db_post_id > 0 ) {
            content_curator_DB::update_status( $db_post_id, 'processed' );
        }

        wp_send_json_success( array(
            'message'  => $publish_status === 'publish'
                ? __( 'Post published successfully!', 'wp-content-curator' )
                : __( 'Post saved as draft!', 'wp-content-curator' ),
            'post_id'  => $new_post_id,
            'post_url' => get_permalink( $new_post_id ),
            'edit_url' => get_edit_post_link( $new_post_id, 'raw' ),
        ) );
    }

    /**
     * AJAX: Manually trigger a fetch of Facebook posts.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_fetch_now() {
        // Verify nonce.
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }

        // Verify capability  settings-level permission for manual fetch.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $result = content_curator_Cron::run_fetch();

        if ( ! empty( $result['errors'] ) ) {
            wp_send_json_error( array(
                'message' => sprintf(
                    /* translators: 1: fetched count, 2: error messages */
                    __( 'Fetch completed with errors. %1$d new posts fetched. Errors: %2$s', 'wp-content-curator' ),
                    $result['fetched'],
                    implode( ' | ', $result['errors'] )
                ),
            ) );
        }

        wp_send_json_success( array(
            'message' => sprintf(
                /* translators: %d: number of new posts fetched */
                __( 'Fetch completed. %d new posts added.', 'wp-content-curator' ),
                $result['fetched']
            ),
            'fetched' => $result['fetched'],
        ) );
    }

    /**
     * AJAX: Delete a curated post completely from the custom table.
     *
     * Expected POST params: post_id, nonce.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_delete() {
        // Verify nonce.
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }

        // Verify capability.
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

        if ( ! $post_id ) {
            wp_send_json_error( array( 'message' => __( 'Invalid post ID.', 'wp-content-curator' ) ) );
        }

        $deleted = content_curator_DB::delete_post( $post_id );

        if ( ! $deleted ) {
            wp_send_json_error( array( 'message' => __( 'Failed to delete the post from database.', 'wp-content-curator' ) ) );
        }

        wp_send_json_success( array(
            'message' => __( 'Post deleted successfully!', 'wp-content-curator' ),
            'post_id' => $post_id,
        ) );
    }

    /**
     * AJAX: Delete all pending curated posts from the custom table.
     *
     * Expected POST params: nonce.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_delete_all() {
        // Verify nonce.
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }

        // Verify capability.
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $deleted = content_curator_DB::delete_all_pending();

        if ( false === $deleted ) {
            wp_send_json_error( array( 'message' => __( 'Failed to delete pending posts.', 'wp-content-curator' ) ) );
        }

        wp_send_json_success( array(
            'message' => sprintf(
                /* translators: %d: number of deleted posts */
                __( 'Successfully deleted %d pending posts.', 'wp-content-curator' ),
                $deleted
            ),
        ) );
    }

    /**
     * AJAX: Change the plugin UI language.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_change_plugin_lang() {
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => 'Security check failed.' ), 403 );
        }
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => 'Insufficient permissions.' ), 403 );
        }
        $lang = isset( $_POST['lang'] ) ? sanitize_text_field( wp_unslash( $_POST['lang'] ) ) : 'en';
        if ( in_array( $lang, array( 'en', 'es', 'fr' ), true ) ) {
            update_option( 'content_curator_plugin_language', $lang );
            wp_send_json_success();
        } else {
            wp_send_json_error( array( 'message' => 'Invalid language.' ) );
        }
    }

    // =========================================================================
    // IMAGE SIDELOADING
    // =========================================================================

    /**
     * Download a remote image and add it to the WordPress media library.
     *
     * Facebook image URLs often lack file extensions and expire.
     * This method handles those edge cases.
     *
     * @param string $image_url The remote image URL.
     * @param int    $post_id   The WordPress post ID to attach the image to.
     * @return int|WP_Error The attachment ID on success, WP_Error on failure.
     */
    public static function sideload_image( $image_url, $post_id ) {
        // Include required WordPress media handling files.
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        // Download the image to a temporary file.
        $tmp_file = download_url( $image_url, 30 );

        if ( is_wp_error( $tmp_file ) ) {
            return $tmp_file;
        }

        // Determine a filename  Facebook URLs may not have a clean extension.
        $filename = basename( wp_parse_url( $image_url, PHP_URL_PATH ) );
        if ( ! preg_match( '/\.(jpe?g|png|gif|webp)$/i', $filename ) ) {
            // Detect MIME type from the downloaded file and assign extension.
            $mime = wp_check_filetype_and_ext( $tmp_file, $filename );
            $ext  = $mime['ext'] ? $mime['ext'] : 'jpg';
            $filename = 'content-curator-' . $post_id . '-' . time() . '.' . $ext;
        }

        $file_array = array(
            'name'     => sanitize_file_name( $filename ),
            'tmp_name' => $tmp_file,
        );

        // Use media_handle_sideload for full control over the attachment.
        $attachment_id = media_handle_sideload( $file_array, $post_id );

        // Clean up temp file on error.
        if ( is_wp_error( $attachment_id ) ) {
            @unlink( $file_array['tmp_name'] );
        }

        return $attachment_id;
    }
}
