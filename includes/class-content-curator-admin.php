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
                'all_time'             => 'All',
                'last_week'            => 'Last week',
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
                'tab_basic'            => 'Basic Configuration',
                'tab_ai'               => 'AI Configuration',
                'tab_cron'             => 'CRON',
                'apify_section_title'  => 'Apify & Page Configuration',
                'apify_section_desc'   => 'Enter your Apify API credentials to fetch public Facebook Page posts.',
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
                'event_start_label'    => 'Start Date',
                'event_end_label'      => 'End Date',
                'event_location_label' => 'Location (Lugar)',
                'event_location_placeholder' => 'Enter location or venue name',
                'categorias_agenda_label' => 'Agenda Categories:',
                'concellos_eventos_label' => 'Concellos (Events):',
                'multiselect_help'     => 'Hold Ctrl (Cmd on Mac) to select multiple options.',
                'tab_agenda'            => 'Pages & Events',
                'agenda_defaults_title' => 'Event Configuration Defaults by Facebook Page',
                'agenda_defaults_desc' => 'Configure monitored Facebook pages along with optional default event start/end dates and location. When posts from these pages are loaded, their agenda fields will be pre-filled automatically.',
                'col_fb_page'           => 'Facebook Page URL/Username',
                'col_use_today'         => 'Today',
                'col_start_date'        => 'Default Start Date',
                'col_end_date'          => 'Default End Date',
                'col_location'          => 'Default Place (Lugar)',
                'btn_add_row'           => 'Add Default Config',
                'btn_remove_row'        => 'Remove',
                'include_gallery_label' => 'Include gallery images',
                'include_cover_label'   => 'Include cover image (Featured)',
                'title_label'           => 'Title',
                'content_label'         => 'Content',
                'title_empty'           => 'Post title cannot be empty.',
                'test_ai_btn'           => 'Test AI Service',
                'testing_ai'            => 'Testing connection...',
                'test_ai_success'       => 'Connection successful! AI response received.',
                'test_ai_error'         => 'Connection failed: ',
                'openai_model_label'    => 'OpenAI Model',
                'anthropic_model_label' => 'Anthropic Model',
                'gemini_model_label'    => 'Gemini Model',
                'openai_model_desc'     => 'Select the OpenAI model to use for AI-assisted content generation.',
                'anthropic_model_desc'  => 'Select the Anthropic Claude model to use for AI-assisted content generation.',
                'gemini_model_desc'     => 'Select the Google Gemini model to use for AI-assisted content generation.',
                'publish_date_label'    => 'Publish Date (Scheduled)',
                'fetching_pages_list'   => 'Getting page list...',
                'fetching_page_x_of_y'  => 'Fetching page %1$d of %2$d (%3$s)...',
                'fetch_completed_summary' => 'Fetch completed: %1$d new posts imported across %2$d pages.',
                'no_pages_configured'   => 'No pages configured in settings.',
                // History section
                'tab_history'              => 'History',
                'history_title'            => 'Publication History',
                'history_desc'             => 'Browse all processed and ignored posts from the curation queue.',
                'status_processed'         => 'Published/Draft',
                'status_ignored'           => 'Ignored',
                'status_all_history'       => 'All Statuses',
                'export_excel'             => 'Export to CSV',
                'no_history'               => 'No history records found',
                'no_history_desc'          => 'Processed and ignored posts will appear here.',
                'col_id'                   => 'ID',
                'col_page'                 => 'Page',
                'col_preview'              => 'Original Text',
                'col_status'               => 'Status',
                'col_fetched'              => 'Fetched At',
                'col_fb_date'              => 'FB Date',
                'col_actions'              => 'Actions',
                'history_mark_pending'     => 'Re-queue',
                'history_view_text'        => 'View Text',
                'history_requeue_confirm'  => 'Move this post back to the pending queue?',
                'history_requeue_success'  => 'Post re-queued successfully!',
                'history_requeue_error'    => 'Failed to re-queue the post.',
                'exporting'                => 'Exporting...',
                'modal_close'              => 'Close',
            ),
            'es' => array(
                'dashboard_title'      => 'Panel de Curación de Contenidos',
                'dashboard_desc'       => 'Revisa, edita y optimiza tus publicaciones de Facebook extraídas con Inteligencia Artificial.',
                'time'                 => 'Tiempo:',
                'site'                 => 'Sitio:',
                'from'                 => 'Desde:',
                'to'                   => 'Hasta:',
                'clear_filters'        => 'Limpiar Filtros',
                'fetch_now'            => 'Escanear ahora',
                'all_time'             => 'Todos',
                'last_week'            => 'Última semana',
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
                'tab_basic'            => 'Configuración Básica',
                'tab_ai'               => 'Configuración IA',
                'tab_cron'             => 'CRON',
                'apify_section_title'  => 'Configuración de Apify y Páginas',
                'apify_section_desc'   => 'Introduce tus credenciales de Apify para la extracción de publicaciones de páginas públicas de Facebook.',
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
                'event_start_label'    => 'Fecha de inicio',
                'event_end_label'      => 'Fecha de fin',
                'event_location_label' => 'Lugar',
                'event_location_placeholder' => 'Introduce el lugar o dirección',
                'categorias_agenda_label' => 'Categorías agenda:',
                'concellos_eventos_label' => 'Concellos eventos:',
                'multiselect_help'     => 'Mantén presionado Ctrl (Cmd en Mac) para seleccionar varios.',
                'tab_agenda'            => 'Páginas y Eventos',
                'agenda_defaults_title' => 'Configuración de Eventos por Página de Facebook',
                'agenda_defaults_desc' => 'Configura las páginas de Facebook a monitorear junto con las fechas de inicio/fin y lugar por defecto (opcional). Cuando se carguen las publicaciones de estas páginas, sus campos de agenda se rellenarán automáticamente.',
                'col_fb_page'           => 'URL/Usuario de Página de Facebook',
                'col_use_today'         => 'Hoy',
                'col_start_date'        => 'Fecha de Inicio por Defecto',
                'col_end_date'          => 'Fecha de Fin por Defecto',
                'col_location'          => 'Lugar por Defecto',
                'btn_add_row'           => 'Añadir Configuración por Defecto',
                'btn_remove_row'        => 'Eliminar',
                'include_gallery_label' => 'Incluir galería de imágenes',
                'include_cover_label'   => 'Incluir imagen de cabecera',
                'title_label'           => 'Titular',
                'content_label'         => 'Contenido',
                'title_empty'           => 'El titular del post no puede estar vacío.',
                'test_ai_btn'           => 'Probar Servicio de IA',
                'testing_ai'            => 'Probando conexión...',
                'test_ai_success'       => '¡Conexión exitosa! Respuesta de IA recibida.',
                'test_ai_error'         => 'Error de conexión: ',
                'openai_model_label'    => 'Modelo de OpenAI',
                'anthropic_model_label' => 'Modelo de Anthropic',
                'gemini_model_label'    => 'Modelo de Gemini',
                'openai_model_desc'     => 'Selecciona el modelo de OpenAI para la generación de contenido asistida por IA.',
                'anthropic_model_desc'  => 'Selecciona el modelo Claude de Anthropic para la generación de contenido asistida por IA.',
                'gemini_model_desc'     => 'Selecciona el modelo Google Gemini para la generación de contenido asistida por IA.',
                'publish_date_label'    => 'Fecha de Publicación',
                'fetching_pages_list'   => 'Obteniendo lista de páginas...',
                'fetching_page_x_of_y'  => 'Escaneando página %1$d de %2$d (%3$s)...',
                'fetch_completed_summary' => 'Escaneo completado: %1$d nuevas publicaciones importadas en %2$d páginas.',
                'no_pages_configured'   => 'No hay páginas configuradas en los ajustes.',
                // Sección historial
                'tab_history'              => 'Historial',
                'history_title'            => 'Historial de Publicaciones',
                'history_desc'             => 'Consulta todas las publicaciones procesadas e ignoradas de la cola de curación.',
                'status_processed'         => 'Publicado/Borrador',
                'status_ignored'           => 'Ignorado',
                'status_all_history'       => 'Todos los estados',
                'export_excel'             => 'Exportar a CSV',
                'no_history'               => 'No se encontraron registros en el historial',
                'no_history_desc'          => 'Aquí aparecerán las publicaciones procesadas e ignoradas.',
                'col_id'                   => 'ID',
                'col_page'                 => 'Página',
                'col_preview'              => 'Texto Original',
                'col_status'               => 'Estado',
                'col_fetched'              => 'Importado',
                'col_fb_date'              => 'Fecha FB',
                'col_actions'              => 'Acciones',
                'history_mark_pending'     => 'Volver a cola',
                'history_view_text'        => 'Ver Texto',
                'history_requeue_confirm'  => '¿Mover esta publicación de vuelta a la cola de pendientes?',
                'history_requeue_success'  => '¡Publicación enviada de vuelta a la cola!',
                'history_requeue_error'    => 'Error al devolver la publicación a la cola.',
                'exporting'                => 'Exportando...',
                'modal_close'              => 'Cerrar',
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
                'all_time'             => 'Tous',
                'last_week'            => 'La semaine dernière',
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
                'tab_basic'            => 'Configuration de Base',
                'tab_ai'               => 'Configuration d\'IA',
                'tab_cron'             => 'CRON',
                'apify_section_title'  => 'Configuration d\'Apify et des Pages',
                'apify_section_desc'   => 'Entrez vos identifiants API Apify pour récupérer les publications des pages Facebook publiques.',
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
                'event_start_label'    => 'Date de début',
                'event_end_label'      => 'Date de fin',
                'event_location_label' => 'Lieu (Lugar)',
                'event_location_placeholder' => 'Entrez le lieu ou l\'adresse',
                'categorias_agenda_label' => 'Catégories d\'agenda:',
                'concellos_eventos_label' => 'Concellos (Événements):',
                'multiselect_help'     => 'Maintenez Ctrl (Cmd sur Mac) pour en en sélectionner plusieurs.',
                'tab_agenda'            => 'Pages & Événements',
                'agenda_defaults_title' => 'Configuration des Événements par Page Facebook',
                'agenda_defaults_desc' => 'Configurez les pages Facebook à surveiller ainsi que la date de début/fin et le lieu par défaut (facultatif). Lorsque les publications de ces pages sont chargées, leurs champs d\'agenda seront pré-remplis automatiquement.',
                'col_fb_page'           => 'URL/Nom d\'utilisateur de Page Facebook',
                'col_use_today'         => 'Aujourd\'hui',
                'col_start_date'        => 'Date de Début par Défaut',
                'col_end_date'          => 'Date de Fin par Défaut',
                'col_location'          => 'Lieu par Défaut',
                'btn_add_row'           => 'Ajouter Configuration par Défaut',
                'btn_remove_row'        => 'Supprimer',
                'include_gallery_label' => 'Inclure la galerie d\'images',
                'include_cover_label'   => "Inclure l'image de couverture",
                'title_label'           => 'Titre',
                'content_label'         => 'Contenu',
                'title_empty'           => 'Le titre de l\'article ne peut pas être vide.',
                'test_ai_btn'           => 'Tester le Service d\'IA',
                'testing_ai'            => 'Test de connexion...',
                'test_ai_success'       => 'Connexion réussie! Réponse de l\'IA reçue.',
                'test_ai_error'         => 'Échec de connexion: ',
                'openai_model_label'    => 'Modèle OpenAI',
                'anthropic_model_label' => 'Modèle Anthropic',
                'gemini_model_label'    => 'Modèle Gemini',
                'openai_model_desc'     => 'Sélectionnez le modèle OpenAI à utiliser pour la génération de contenu assistée par IA.',
                'anthropic_model_desc'  => 'Sélectionnez le modèle Claude d\'Anthropic pour la génération de contenu assistée par IA.',
                'gemini_model_desc'     => 'Sélectionnez le modèle Google Gemini pour la génération de contenu assistée par IA.',
                'publish_date_label'    => 'Date de Publication',
                'fetching_pages_list'   => 'Obtention de la liste des pages...',
                'fetching_page_x_of_y'  => 'Récupération de la page %1$d sur %2$d (%3$s)...',
                'fetch_completed_summary' => 'Récupération terminée : %1$d nouvelles publications importées sur %2$d pages.',
                'no_pages_configured'   => 'Aucune page configurée dans les paramètres.',
                // Section historique
                'tab_history'              => 'Historique',
                'history_title'            => 'Historique des Publications',
                'history_desc'             => 'Parcourez toutes les publications traitées et ignorées de la file de curation.',
                'status_processed'         => 'Publié/Brouillon',
                'status_ignored'           => 'Ignoré',
                'status_all_history'       => 'Tous les statuts',
                'export_excel'             => 'Exporter en CSV',
                'no_history'               => 'Aucun enregistrement trouvé dans l\'historique',
                'no_history_desc'          => 'Les publications traitées et ignorées apparaîtront ici.',
                'col_id'                   => 'ID',
                'col_page'                 => 'Page',
                'col_preview'              => 'Texte Original',
                'col_status'               => 'Statut',
                'col_fetched'              => 'Importé le',
                'col_fb_date'              => 'Date FB',
                'col_actions'              => 'Actions',
                'history_mark_pending'     => 'Remettre en file',
                'history_view_text'        => 'Voir le Texte',
                'history_requeue_confirm'  => 'Remettre cette publication dans la file d\'attente ?',
                'history_requeue_success'  => 'Publication remise en file avec succès !',
                'history_requeue_error'    => 'Échec de la remise en file de la publication.',
                'exporting'                => 'Exportation...',
                'modal_close'              => 'Fermer',
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
        add_action( 'wp_ajax_content_curator_test_ai', array( $this, 'ajax_test_ai' ) );
        add_action( 'wp_ajax_content_curator_get_pages_to_fetch', array( $this, 'ajax_get_pages_to_fetch' ) );
        add_action( 'wp_ajax_content_curator_fetch_single_page', array( $this, 'ajax_fetch_single_page' ) );
        // History AJAX handlers.
        add_action( 'wp_ajax_content_curator_export_history', array( $this, 'ajax_export_history' ) );
        add_action( 'wp_ajax_content_curator_history_update_status', array( $this, 'ajax_history_update_status' ) );
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

        // Submenu: History.
        add_submenu_page(
            'content-curator-dashboard',
            __( 'Publication History', 'wp-content-curator' ),
            __( 'History', 'wp-content-curator' ),
            'edit_posts',
            'content-curator-history',
            array( $this, 'render_history_page' )
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

        register_setting( 'content_curator_settings_group', 'content_curator_openai_model', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'gpt-4o-mini',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_anthropic_model', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'claude-3-haiku-20240307',
        ) );

        register_setting( 'content_curator_settings_group', 'content_curator_gemini_model', array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'gemini-1.5-flash',
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

        add_settings_field(
            'content_curator_openai_model',
            $d['openai_model_label'],
            array( $this, 'render_field_openai_model' ),
            'content-curator-settings',
            'content_curator_ai_section'
        );

        add_settings_field(
            'content_curator_anthropic_model',
            $d['anthropic_model_label'],
            array( $this, 'render_field_anthropic_model' ),
            'content-curator-settings',
            'content_curator_ai_section'
        );

        add_settings_field(
            'content_curator_gemini_model',
            $d['gemini_model_label'],
            array( $this, 'render_field_gemini_model' ),
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

        register_setting( 'content_curator_settings_group', 'content_curator_agenda_defaults', array(
            'type'              => 'array',
            'sanitize_callback' => array( $this, 'sanitize_agenda_defaults' ),
            'default'           => array(),
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
     * Sanitize the agenda default values array.
     */
    public function sanitize_agenda_defaults( $value ) {
        if ( ! is_array( $value ) ) {
            return array();
        }
        $sanitized = array();
        foreach ( $value as $item ) {
            $page_id = isset( $item['page_id'] ) ? sanitize_text_field( wp_unslash( $item['page_id'] ) ) : '';
            if ( empty( $page_id ) ) {
                continue;
            }
            $sanitized[] = array(
                'page_id'    => $page_id,
                'use_today'  => isset( $item['use_today'] ) ? 1 : 0,
                'start_date' => isset( $item['start_date'] ) ? sanitize_text_field( wp_unslash( $item['start_date'] ) ) : '',
                'end_date'   => isset( $item['end_date'] ) ? sanitize_text_field( wp_unslash( $item['end_date'] ) ) : '',
                'location'   => isset( $item['location'] ) ? sanitize_text_field( wp_unslash( $item['location'] ) ) : '',
            );
        }
        return $sanitized;
    }

    /**
     * Render the Agenda Defaults dynamic table field.
     */
    public function render_field_agenda_defaults() {
        $value = get_option( 'content_curator_agenda_defaults', array() );
        if ( ! is_array( $value ) ) {
            $value = array();
        }
        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d = self::get_dictionary( $plugin_lang );
        ?>
        <div class="cc-agenda-defaults-table-container">
            <table class="wp-list-table widefat fixed striped table-view-list" id="cc-agenda-defaults-table" style="max-width: 900px; margin-bottom: 15px;">
                <thead>
                    <tr>
                        <th style="width: 25%;"><?php echo esc_html( $d['col_fb_page'] ); ?></th>
                        <th style="width: 10%; text-align: center;"><?php echo esc_html( $d['col_use_today'] ); ?></th>
                        <th style="width: 20%;"><?php echo esc_html( $d['col_start_date'] ); ?></th>
                        <th style="width: 20%;"><?php echo esc_html( $d['col_end_date'] ); ?></th>
                        <th style="width: 20%;"><?php echo esc_html( $d['col_location'] ); ?></th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $value ) ) : ?>
                        <tr class="cc-no-defaults-row">
                            <td colspan="6" style="text-align: center; color: var(--cc-text-muted); padding: 15px;">
                                <?php esc_html_e( 'No default configurations added yet.', 'wp-content-curator' ); ?>
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ( $value as $index => $item ) : ?>
                            <tr>
                                <td>
                                    <input type="text" name="content_curator_agenda_defaults[<?php echo $index; ?>][page_id]" value="<?php echo esc_attr( $item['page_id'] ); ?>" placeholder="e.g. concellodecamarinas" required />
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <input type="checkbox" name="content_curator_agenda_defaults[<?php echo $index; ?>][use_today]" value="1" <?php checked( ! empty( $item['use_today'] ) ); ?> class="cc-use-today-checkbox" />
                                </td>
                                <td>
                                    <input type="date" name="content_curator_agenda_defaults[<?php echo $index; ?>][start_date]" value="<?php echo esc_attr( $item['start_date'] ?? '' ); ?>" <?php disabled( ! empty( $item['use_today'] ) ); ?> />
                                </td>
                                <td>
                                    <input type="date" name="content_curator_agenda_defaults[<?php echo $index; ?>][end_date]" value="<?php echo esc_attr( $item['end_date'] ?? '' ); ?>" <?php disabled( ! empty( $item['use_today'] ) ); ?> />
                                </td>
                                <td>
                                    <input type="text" name="content_curator_agenda_defaults[<?php echo $index; ?>][location]" value="<?php echo esc_attr( $item['location'] ?? '' ); ?>" placeholder="e.g. Salón de Plenos" />
                                </td>
                                <td>
                                    <button type="button" class="button button-link-delete cc-remove-agenda-row-btn" title="<?php echo esc_attr( $d['btn_remove_row'] ); ?>">
                                        <span class="dashicons dashicons-trash" style="color: #b32d2e; vertical-align: middle;"></span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="button" class="button button-secondary" id="cc-add-agenda-row-btn">
                <span class="dashicons dashicons-plus" style="vertical-align: middle; margin-right: 4px;"></span>
                <?php echo esc_html( $d['btn_add_row'] ); ?>
            </button>
        </div>
        <?php
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
     * Render the OpenAI Model field.
     */
    public function render_field_openai_model() {
        $value       = get_option( 'content_curator_openai_model', 'gpt-4o-mini' );
        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d           = self::get_dictionary( $plugin_lang );
        $models      = array(
            'GPT-4o'              => array(
                'gpt-4o'          => 'GPT-4o',
                'gpt-4o-mini'     => 'GPT-4o mini (recommended)',
            ),
            'GPT-4 Turbo'         => array(
                'gpt-4-turbo'     => 'GPT-4 Turbo',
            ),
            'GPT-3.5'             => array(
                'gpt-3.5-turbo'   => 'GPT-3.5 Turbo',
            ),
            'o-series'            => array(
                'o1-mini'         => 'o1-mini',
                'o3-mini'         => 'o3-mini',
            ),
        );
        echo '<select id="content_curator_openai_model" name="content_curator_openai_model">';
        foreach ( $models as $group_label => $group_models ) {
            echo '<optgroup label="' . esc_attr( $group_label ) . '">';
            foreach ( $group_models as $model_id => $model_name ) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr( $model_id ),
                    selected( $value, $model_id, false ),
                    esc_html( $model_name )
                );
            }
            echo '</optgroup>';
        }
        echo '</select>';
        printf( '<p class="description">%s</p>', esc_html( $d['openai_model_desc'] ) );
    }

    /**
     * Render the Anthropic Model field.
     */
    public function render_field_anthropic_model() {
        $value       = get_option( 'content_curator_anthropic_model', 'claude-3-haiku-20240307' );
        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d           = self::get_dictionary( $plugin_lang );
        $models      = array(
            'Claude 3.5'          => array(
                'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet',
                'claude-3-5-haiku-20241022'  => 'Claude 3.5 Haiku (recommended)',
            ),
            'Claude 3'            => array(
                'claude-3-opus-20240229'     => 'Claude 3 Opus',
                'claude-3-sonnet-20240229'   => 'Claude 3 Sonnet',
                'claude-3-haiku-20240307'    => 'Claude 3 Haiku',
            ),
        );
        echo '<select id="content_curator_anthropic_model" name="content_curator_anthropic_model">';
        foreach ( $models as $group_label => $group_models ) {
            echo '<optgroup label="' . esc_attr( $group_label ) . '">';
            foreach ( $group_models as $model_id => $model_name ) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr( $model_id ),
                    selected( $value, $model_id, false ),
                    esc_html( $model_name )
                );
            }
            echo '</optgroup>';
        }
        echo '</select>';
        printf( '<p class="description">%s</p>', esc_html( $d['anthropic_model_desc'] ) );
    }

    /**
     * Render the Gemini Model field.
     */
    public function render_field_gemini_model() {
        $value       = get_option( 'content_curator_gemini_model', 'gemini-1.5-flash' );
        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d           = self::get_dictionary( $plugin_lang );
        $models      = array(
            'Gemini 2.0'          => array(
                'gemini-2.0-flash'         => 'Gemini 2.0 Flash (recommended)',
                'gemini-2.0-flash-lite'    => 'Gemini 2.0 Flash Lite',
            ),
            'Gemini 1.5'          => array(
                'gemini-1.5-pro'           => 'Gemini 1.5 Pro',
                'gemini-1.5-flash'         => 'Gemini 1.5 Flash',
                'gemini-1.5-flash-8b'      => 'Gemini 1.5 Flash 8B',
            ),
        );
        echo '<select id="content_curator_gemini_model" name="content_curator_gemini_model">';
        foreach ( $models as $group_label => $group_models ) {
            echo '<optgroup label="' . esc_attr( $group_label ) . '">';
            foreach ( $group_models as $model_id => $model_name ) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    esc_attr( $model_id ),
                    selected( $value, $model_id, false ),
                    esc_html( $model_name )
                );
            }
            echo '</optgroup>';
        }
        echo '</select>';
        printf( '<p class="description">%s</p>', esc_html( $d['gemini_model_desc'] ) );
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
            'content-curator_page_content-curator-history',
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

        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d = self::get_dictionary( $plugin_lang );

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
                    'title_empty'        => __( 'Post title cannot be empty.', 'wp-content-curator' ),
                    'testing_ai'         => $d['testing_ai'],
                    'test_ai_success'    => $d['test_ai_success'],
                    'test_ai_error'      => $d['test_ai_error'],
                    'fetching_pages_list' => $d['fetching_pages_list'],
                    'fetching_page_x_of_y' => $d['fetching_page_x_of_y'],
                    'fetch_completed_summary' => $d['fetch_completed_summary'],
                    'no_pages_configured' => $d['no_pages_configured'],
                    // History strings.
                    'history_requeue_confirm' => $d['history_requeue_confirm'],
                    'history_requeue_success' => $d['history_requeue_success'],
                    'history_requeue_error'   => $d['history_requeue_error'],
                    'exporting'               => $d['exporting'],
                    'modal_close'             => $d['modal_close'],
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
                    <a href="#tab-agenda" class="nav-tab" data-tab="agenda"><?php echo esc_html( $d['tab_agenda'] ); ?></a>
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
                        <div style="margin-top: 15px; display: flex; align-items: center; gap: 10px;">
                            <button type="button" id="content-curator-test-ai" class="button button-secondary">
                                <span class="dashicons dashicons-admin-network" style="vertical-align: middle; margin-right: 4px;"></span>
                                <?php echo esc_html( $d['test_ai_btn'] ); ?>
                            </button>
                            <span id="content-curator-test-ai-status" class="content-curator-inline-status"></span>
                        </div>
                    </div>

                    <!-- Tab 3: Event Configuration -->
                    <div id="tab-agenda" class="settings-tab-content" style="display: none;">
                        <p class="description" style="margin-bottom: 15px; font-size: 13px;"><?php echo esc_html( $d['agenda_defaults_desc'] ); ?></p>
                        <?php $this->render_field_agenda_defaults(); ?>
                    </div>

                    <!-- Tab 4: CRON Configuration -->
                    <div id="tab-cron" class="settings-tab-content" style="display: none;">
                        <p class="description" style="margin-bottom: 15px; font-size: 13px;"><?php echo esc_html( $d['cron_section_desc'] ); ?></p>
                        <table class="form-table" role="presentation">
                            <?php do_settings_fields( 'content-curator-settings', 'content_curator_cron_section' ); ?>
                        </table>
                        <?php
                        // Show next scheduled run.
                        $next = wp_next_scheduled( content_curator_Cron::CRON_HOOK );
                        if ( $next ) {
                            printf(
                                '<p class="description" style="margin-top: 15px; font-size: 13px;">%s <strong>%s</strong></p>',
                                esc_html__( 'Next scheduled fetch:', 'wp-content-curator' ),
                                esc_html( wp_date( 'Y-m-d H:i:s', $next ) )
                            );
                        }
                        ?>
                    </div>
                </div>

                <?php submit_button( __( 'Save Settings', 'wp-content-curator' ) ); ?>
            </form>
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

            <!-- Control & Actions Bar (Fetch & Status) -->
            <div class="content-curator-actions-bar">
                <div class="actions-bar-left">
                    <span class="content-curator-count-badge">
                        <span class="dashicons dashicons-clipboard" style="margin-right: 6px; font-size: 16px; width: 16px; height: 16px; vertical-align: middle; color: var(--cc-primary);"></span>
                        <?php
                        $count_string = $total_posts === 1 ? $d['pending_posts'] : $d['pending_posts_plural'];
                        printf( '<strong>%d</strong>&nbsp;%s', $total_posts, esc_html( $count_string ) );
                        ?>
                    </span>
                </div>
                <div class="actions-bar-right">
                    <span id="content-curator-fetch-status" class="content-curator-inline-status"></span>
                    <select id="content-curator-fetch-timeframe" class="fetch-timeframe-select">
                        <option value="all"><?php echo esc_html( $d['all_time'] ); ?></option>
                        <option value="24h"><?php echo esc_html( $d['last_24'] ); ?></option>
                        <option value="7d"><?php echo esc_html( $d['last_week'] ); ?></option>
                    </select>
                    <button type="button" id="content-curator-fetch-now" class="button button-primary fetch-now-btn">
                        <span class="dashicons dashicons-download" style="vertical-align: middle;"></span>
                        <?php echo esc_html( $d['fetch_now'] ); ?>
                    </button>
                    <?php if ( $total_posts > 0 ) : ?>
                        <button type="button" id="content-curator-delete-all" class="button btn-delete-all">
                            <span class="dashicons dashicons-trash" style="vertical-align: middle;"></span>
                            <?php echo esc_html( $d['delete_all'] ); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Toolbar Form: Filter Curation Cards -->
            <form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="content-curator-toolbar-form">
                <input type="hidden" name="page" value="content-curator-dashboard" />

                <div class="content-curator-filters-bar">
                    <div class="filters-bar-title">
                        <span class="dashicons dashicons-filter" style="font-size: 16px; width: 16px; height: 16px; color: var(--cc-text-secondary); vertical-align: middle; margin-right: 4px;"></span>
                        <strong><?php echo esc_html__( 'Filters', 'wp-content-curator' ); ?></strong>
                    </div>

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

                    <?php if ( ! empty( $start_date ) || ! empty( $end_date ) || 'all' !== $filter || 'all' !== $site_filter ) : ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=content-curator-dashboard' ) ); ?>" class="button button-secondary clear-filters-btn" style="margin-left: 10px;">
                            <?php echo esc_html( $d['clear_filters'] ); ?>
                        </a>
                    <?php endif; ?>
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
                    <?php 
                    $agenda_defaults = get_option( 'content_curator_agenda_defaults', array() );
                    foreach ( $posts as $post ) : 
                        $card_defaults = array(
                            'use_today'  => 0,
                            'start_date' => '',
                            'end_date'   => '',
                            'location'   => '',
                        );
                        if ( is_array( $agenda_defaults ) ) {
                            foreach ( $agenda_defaults as $def ) {
                                $def_page = trim( strtolower( $def['page_id'] ?? '' ) );
                                $curr_page = trim( strtolower( $post->page_name ) );
                                if ( $def_page === $curr_page || strpos( $curr_page, $def_page ) !== false || strpos( $def_page, $curr_page ) !== false ) {
                                    $card_defaults = array(
                                        'use_today'  => ! empty( $def['use_today'] ) ? 1 : 0,
                                        'start_date' => $def['start_date'] ?? '',
                                        'end_date'   => $def['end_date'] ?? '',
                                        'location'   => $def['location'] ?? '',
                                    );
                                    break;
                                }
                            }
                        }

                        $start_val = '';
                        $end_val = '';
                        if ( $card_defaults['use_today'] ) {
                            $start_val = wp_date( 'Y-m-d' );
                            $end_val   = wp_date( 'Y-m-d' );
                        } else {
                            if ( ! empty( $card_defaults['start_date'] ) ) {
                                $start_val = $card_defaults['start_date'];
                            }
                            if ( ! empty( $card_defaults['end_date'] ) ) {
                                $end_val = $card_defaults['end_date'];
                            }
                        }
                        
                        $location_val = $card_defaults['location'];
                    ?>
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
                                    <div class="card-image-gallery-wrap">
                                        <div class="card-image-gallery-container">
                                            <div class="card-image-gallery">
                                                <?php foreach ( $images as $idx => $img_url ) : ?>
                                                    <div class="card-gallery-image <?php echo $idx === 0 ? 'active' : ''; ?>">
                                                        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $d['facebook_image'] ); ?>" loading="lazy" />
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php if ( count( $images ) > 1 ) : ?>
                                            <div class="gallery-controls">
                                                <button type="button" class="gallery-prev" onclick="changeGalleryImage(this, -1);">&lsaquo;</button>
                                                <span class="gallery-counter">1 / <?php echo count( $images ); ?></span>
                                                <button type="button" class="gallery-next" onclick="changeGalleryImage(this, 1);">&rsaquo;</button>
                                            </div>
                                        <?php endif; ?>
                                        <div class="image-toggles">
                                            <div class="cover-toggle" style="display: flex; align-items: center; gap: 8px;">
                                                <input type="checkbox" id="cover-toggle-<?php echo esc_attr( $post->id ); ?>" class="include-cover-checkbox" checked="checked" value="1" />
                                                <label for="cover-toggle-<?php echo esc_attr( $post->id ); ?>" style="font-size: 12px; font-weight: 600; color: var(--cc-text-secondary); cursor: pointer;"><?php echo esc_html( $d['include_cover_label'] ); ?></label>
                                            </div>
                                            <?php if ( count( $images ) > 1 ) : ?>
                                                <div class="gallery-toggle" style="display: flex; align-items: center; gap: 8px;">
                                                    <input type="checkbox" id="gallery-toggle-<?php echo esc_attr( $post->id ); ?>" class="include-gallery-checkbox" checked="checked" value="1" />
                                                    <label for="gallery-toggle-<?php echo esc_attr( $post->id ); ?>" style="font-size: 12px; font-weight: 600; color: var(--cc-text-secondary); cursor: pointer;"><?php echo esc_html( $d['include_gallery_label'] ); ?></label>
                                                </div>
                                            <?php endif; ?>
                                        </div>
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
                                            <?php $first = true; foreach ( $curated_langs as $lang_code ) : 
                                                $lines = preg_split( '/\r\n|\r|\n/', $post->original_text, 2 );
                                                $orig_title = wp_strip_all_tags( $lines[0] );
                                                $orig_title = preg_replace( '/^<h2[^>]*>(.*?)<\/h2>$/i', '$1', $orig_title );
                                                $orig_title = trim( $orig_title );
                                                $orig_body  = isset( $lines[1] ) ? trim( $lines[1] ) : '';
                                            ?>
                                                <div class="editor-tab-content-wrapper <?php echo $first ? 'active' : ''; ?>"
                                                    data-lang="<?php echo esc_attr( $lang_code ); ?>"
                                                    data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                                    style="<?php echo $first ? '' : 'display: none;'; ?>">
                                                    
                                                    <div class="editor-field-group">
                                                        <label><?php echo esc_html( $d['title_label'] ); ?></label>
                                                        <input type="text"
                                                            class="content-curator-title-input"
                                                            data-lang="<?php echo esc_attr( $lang_code ); ?>"
                                                            data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                                            value="<?php echo esc_attr( $orig_title ); ?>"
                                                            placeholder="<?php echo esc_attr( $d['title_label'] ); ?>"
                                                        />
                                                    </div>
                                                    
                                                    <div class="editor-field-group">
                                                        <label><?php echo esc_html( $d['content_label'] ); ?></label>
                                                        <textarea
                                                            class="content-curator-textarea <?php echo $first ? 'active' : ''; ?>"
                                                            data-lang="<?php echo esc_attr( $lang_code ); ?>"
                                                            data-post-id="<?php echo esc_attr( $post->id ); ?>"
                                                            rows="8"
                                                        ><?php echo esc_textarea( $orig_body ); ?></textarea>
                                                    </div>
                                                </div>
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

                                        <div class="meta-select-item">
                                            <label><?php echo esc_html( $d['publish_date_label'] ); ?></label>
                                            <input type="datetime-local" class="input-publish-date" data-post-id="<?php echo esc_attr( $post->id ); ?>" style="padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm); font-size: 12px; color: var(--cc-text-primary); background: var(--cc-bg-surface); box-sizing: border-box; height: 30px;" />
                                        </div>
                                    </div>

                                    <!-- Event Fields Section (for CPT agenda) -->
                                    <div class="agenda-only-fields" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed var(--cc-border);">
                                        <h4 style="margin-bottom: 12px; display: flex; align-items: center; gap: 6px; color: var(--cc-primary); font-size: 13px; font-weight: 600; text-transform: uppercase;">
                                            <span class="dashicons dashicons-calendar-alt"></span>
                                            <?php echo esc_html( $d['event_start_label'] ) . ' & ' . esc_html( $d['event_end_label'] ); ?>
                                        </h4>
                                        
                                        <div class="meta-fields-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                                            <div class="meta-field-item">
                                                <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['event_start_label'] ); ?></label>
                                                <input type="date" class="event-start-date" value="<?php echo esc_attr( $start_val ); ?>" style="width: 100%; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm);" />
                                            </div>
                                            <div class="meta-field-item">
                                                <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['event_end_label'] ); ?></label>
                                                <input type="date" class="event-end-date" value="<?php echo esc_attr( $end_val ); ?>" style="width: 100%; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm);" />
                                            </div>
                                        </div>
                                        
                                        <div class="meta-field-item" style="margin-bottom: 12px;">
                                            <label style="font-size: 11px; font-weight: 600; color: var(--cc-text-secondary); margin-bottom: 4px; display: block;"><?php echo esc_html( $d['event_location_label'] ); ?></label>
                                            <input type="text" class="event-location" value="<?php echo esc_attr( $location_val ); ?>" placeholder="<?php echo esc_attr( $d['event_location_placeholder'] ); ?>" style="width: 100%; padding: 6px; border: 1px solid var(--cc-border); border-radius: var(--cc-radius-sm);" />
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
        $include_gallery = isset( $_POST['include_gallery'] ) ? (bool) $_POST['include_gallery'] : true;
        $include_cover   = isset( $_POST['include_cover'] ) ? (bool) $_POST['include_cover'] : true;
        $publish_date    = isset( $_POST['publish_date'] ) ? sanitize_text_field( wp_unslash( $_POST['publish_date'] ) ) : '';

        $post_date = '';
        $post_date_gmt = '';
        if ( ! empty( $publish_date ) ) {
            $timestamp = strtotime( $publish_date );
            if ( $timestamp ) {
                $post_date = date( 'Y-m-d H:i:s', $timestamp );
                $post_date_gmt = get_gmt_from_date( $post_date );
            }
        }

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
        $master_post_args = array(
            'post_title'   => $title,
            'post_content' => $body,
            'post_status'  => $publish_status,
            'post_type'    => $post_type,
            'post_author'  => get_current_user_id(),
        );
        if ( ! empty( $post_date ) ) {
            $master_post_args['post_date']     = $post_date;
            $master_post_args['post_date_gmt'] = $post_date_gmt;
        }

        $new_post_id = wp_insert_post( $master_post_args, true );

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
            update_post_meta( $new_post_id, 'lugar', $event_location );
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

        // Determine cover and gallery image availability
        $sideload_cover   = ( $include_cover && ! empty( $image_urls ) );
        $sideload_gallery = ( $include_gallery && count( $image_urls ) > 1 );

        $cover_attachment_id = 0;
        $gallery_attachment_ids = array();

        // Sideload cover image
        if ( $sideload_cover ) {
            $cover_url = $image_urls[0];
            $attachment_id = self::sideload_image( $cover_url, $new_post_id, $title );
            if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
                $cover_attachment_id = $attachment_id;
            } else {
                if ( is_wp_error( $attachment_id ) ) {
                    error_log( '[WP FB Curator] Cover image sideload error: ' . $attachment_id->get_error_message() );
                }
            }
        }

        // Sideload gallery images
        if ( $sideload_gallery ) {
            $gallery_urls = array_slice( $image_urls, 1 );
            foreach ( $gallery_urls as $url ) {
                $attachment_id = self::sideload_image( $url, $new_post_id, '' );
                if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
                    $gallery_attachment_ids[] = $attachment_id;
                } else {
                    if ( is_wp_error( $attachment_id ) ) {
                        error_log( '[WP FB Curator] Gallery image sideload error: ' . $attachment_id->get_error_message() );
                    }
                }
            }
        }

        // Combine all successfully sideloaded attachment IDs
        $all_gallery_ids = array();
        if ( $cover_attachment_id > 0 ) {
            $all_gallery_ids[] = $cover_attachment_id;
        }
        if ( ! empty( $gallery_attachment_ids ) ) {
            $all_gallery_ids = array_merge( $all_gallery_ids, $gallery_attachment_ids );
        }

        // Set featured image on master post
        if ( $cover_attachment_id > 0 ) {
            set_post_thumbnail( $new_post_id, $cover_attachment_id );
        }

        // Generate block HTML (gallery block or single image block)
        $gallery_html = '';
        $image_html   = '';
        if ( $sideload_gallery && count( $all_gallery_ids ) > 1 ) {
            $gallery_html = "\n\n<!-- wp:gallery {\"linkTo\":\"none\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-default is-cropped\">";
            foreach ( $all_gallery_ids as $att_id ) {
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
        } elseif ( $sideload_gallery && count( $all_gallery_ids ) === 1 && ! $include_cover ) {
            // Exactly 1 image is included, and it was NOT set as the cover image, so we put it in the content body as a single image.
            $att_id = $all_gallery_ids[0];
            $img_src = wp_get_attachment_url( $att_id );
            $image_html = "\n\n<!-- wp:image {\"id\":" . $att_id . ",\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n";
            $image_html .= "<figure class=\"wp-block-image size-large\"><img src=\"" . esc_url( $img_src ) . "\" alt=\"\" class=\"wp-image-" . $att_id . "\"/></figure>\n";
            $image_html .= "<!-- /wp:image -->";

            $body .= $image_html;
            wp_update_post( array(
                'ID'           => $new_post_id,
                'post_content' => $body,
            ) );
        }

        if ( 'agenda' === $post_type && ! empty( $all_gallery_ids ) ) {
            update_post_meta( $new_post_id, 'galeria-del-evento', implode( ',', $all_gallery_ids ) );
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

            // Append same gallery or image HTML to translation body.
            if ( ! empty( $gallery_html ) ) {
                $lang_body .= $gallery_html;
            } elseif ( ! empty( $image_html ) ) {
                $lang_body .= $image_html;
            }

            $translated_post_args = array(
                'post_title'   => $lang_title,
                'post_content' => $lang_body,
                'post_status'  => $publish_status,
                'post_type'    => $post_type,
                'post_author'  => get_current_user_id(),
            );
            if ( ! empty( $post_date ) ) {
                $translated_post_args['post_date']     = $post_date;
                $translated_post_args['post_date_gmt'] = $post_date_gmt;
            }

            $translated_post_id = wp_insert_post( $translated_post_args, true );

            if ( ! is_wp_error( $translated_post_id ) ) {
                // Set tag.
                if ( ! empty( $tag_value ) && strpos( $tag_value, ':' ) !== false ) {
                    list( $taxonomy, $term_id ) = explode( ':', $tag_value, 2 );
                    wp_set_object_terms( $translated_post_id, array( absint( $term_id ) ), sanitize_key( $taxonomy ) );
                }

                // Set thumbnail.
                if ( $cover_attachment_id > 0 ) {
                    set_post_thumbnail( $translated_post_id, $cover_attachment_id );
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
                    update_post_meta( $translated_post_id, 'lugar', $event_location );
                    update_post_meta( $translated_post_id, 'descripcion-del-evento', $lang_text );
                    if ( ! empty( $all_gallery_ids ) ) {
                        update_post_meta( $translated_post_id, 'galeria-del-evento', implode( ',', $all_gallery_ids ) );
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

        $timeframe = isset( $_POST['timeframe'] ) ? sanitize_text_field( wp_unslash( $_POST['timeframe'] ) ) : 'all';
        $result = content_curator_Cron::run_fetch( $timeframe );

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
     * AJAX: Test connection to the AI service.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_test_ai() {
        // Verify nonce.
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }

        // Verify capability.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $provider = isset( $_POST['provider'] ) ? sanitize_text_field( wp_unslash( $_POST['provider'] ) ) : '';
        $api_key  = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';

        // Call the test connection method
        $result = content_curator_API::test_ai_connection( $provider, $api_key );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d = self::get_dictionary( $plugin_lang );

        wp_send_json_success( array(
            'message' => $d['test_ai_success'],
        ) );
    }

    /**
     * AJAX: Get the list of configured Facebook Pages to fetch.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_get_pages_to_fetch() {
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $agenda_defaults = get_option( 'content_curator_agenda_defaults', array() );
        $page_ids        = array();
        if ( is_array( $agenda_defaults ) ) {
            foreach ( $agenda_defaults as $item ) {
                if ( ! empty( $item['page_id'] ) ) {
                    $page_ids[] = trim( $item['page_id'] );
                }
            }
        }
        $page_ids = array_unique( array_filter( $page_ids ) );

        wp_send_json_success( array(
            'pages' => array_values( $page_ids ),
        ) );
    }

    /**
     * AJAX: Fetch Facebook posts for a single page.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_fetch_single_page() {
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $page_id = isset( $_POST['page_id'] ) ? sanitize_text_field( wp_unslash( $_POST['page_id'] ) ) : '';
        $timeframe = isset( $_POST['timeframe'] ) ? sanitize_text_field( wp_unslash( $_POST['timeframe'] ) ) : 'all';

        if ( empty( $page_id ) ) {
            wp_send_json_error( array( 'message' => __( 'Page ID/URL is empty.', 'wp-content-curator' ) ) );
        }

        $apify_token = get_option( 'content_curator_apify_token', '' );
        if ( empty( $apify_token ) ) {
            wp_send_json_error( array( 'message' => __( 'Apify API token is not configured.', 'wp-content-curator' ) ) );
        }

        // Fetch posts for this single page URL/username
        $posts = content_curator_API::fetch_page_posts( $page_id, $apify_token, 20, $timeframe );

        if ( is_wp_error( $posts ) ) {
            wp_send_json_error( array( 'message' => $posts->get_error_message() ) );
        }

        // Process and insert fetched posts using the extracted method
        $inserted_count = 0;
        if ( is_array( $posts ) && ! empty( $posts ) ) {
            $inserted_count = content_curator_Cron::process_single_page_posts( $page_id, $posts );
        }

        wp_send_json_success( array(
            'message' => sprintf(
                /* translators: 1: Facebook page ID, 2: number of posts fetched */
                __( 'Successfully fetched %1$s: %2$d new posts.', 'wp-content-curator' ),
                $page_id,
                $inserted_count
            ),
            'fetched' => $inserted_count,
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

    // =========================================================================
    // HISTORY PAGE RENDER
    // =========================================================================

    /**
     * Render the Publication History page.
     *
     * @return void
     */
    public function render_history_page() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'wp-content-curator' ) );
        }

        $plugin_lang = get_option( 'content_curator_plugin_language', 'en' );
        $d           = self::get_dictionary( $plugin_lang );

        // Filters from query params.
        $status_filter = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : 'all';
        $allowed_statuses = array( 'all', 'processed', 'ignored' );
        if ( ! in_array( $status_filter, $allowed_statuses, true ) ) {
            $status_filter = 'all';
        }

        $site_filter = isset( $_GET['site'] ) ? sanitize_text_field( wp_unslash( $_GET['site'] ) ) : 'all';
        $start_date  = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash( $_GET['start_date'] ) ) : '';
        $end_date    = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash( $_GET['end_date'] ) ) : '';

        // Pagination.
        $posts_per_page = 20;
        $paged          = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
        $offset         = ( $paged - 1 ) * $posts_per_page;

        // Data fetch.
        $sites       = Content_Curator_DB::get_unique_sites();
        $total_posts = Content_Curator_DB::get_history_posts_count( $status_filter, $site_filter, $start_date, $end_date );
        $posts       = Content_Curator_DB::get_history_posts( $status_filter, $site_filter, $posts_per_page, $offset, $start_date, $end_date );

        $filters_active = ( 'all' !== $status_filter || 'all' !== $site_filter || ! empty( $start_date ) || ! empty( $end_date ) );

        $base_url = admin_url( 'admin.php?page=content-curator-history' );
        ?>
        <div class="wrap content-curator-wrap">

            <!-- Page Banner -->
            <div class="content-curator-dashboard-banner">
                <div class="banner-overlay"></div>
                <div class="banner-content">
                    <img src="<?php echo esc_url( WP_CONTENT_CURATOR_URL . 'assets/images/icon.png' ); ?>" alt="" class="content-curator-banner-icon" />
                    <h1><?php echo esc_html( $d['history_title'] ); ?></h1>
                    <p class="banner-desc"><?php echo esc_html( $d['history_desc'] ); ?></p>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="content-curator-actions-bar">
                <div class="actions-bar-left">
                    <span class="content-curator-count-badge">
                        <span class="dashicons dashicons-backup" style="margin-right: 6px; font-size: 16px; width: 16px; height: 16px; vertical-align: middle; color: var(--cc-primary);"></span>
                        <strong><?php echo absint( $total_posts ); ?></strong>&nbsp;<?php esc_html_e( 'records', 'wp-content-curator' ); ?>
                    </span>
                </div>
                <div class="actions-bar-right">
                    <span id="cc-history-status" class="content-curator-inline-status"></span>
                    <?php if ( $total_posts > 0 ) : ?>
                        <button type="button"
                            id="cc-history-export-btn"
                            class="button cc-export-btn">
                            <span class="dashicons dashicons-download" style="vertical-align: middle;"></span>
                            <?php echo esc_html( $d['export_excel'] ); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Filters Bar -->
            <form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="content-curator-toolbar-form">
                <input type="hidden" name="page" value="content-curator-history" />

                <div class="content-curator-filters-bar">
                    <div class="filters-bar-title">
                        <span class="dashicons dashicons-filter" style="font-size: 16px; width: 16px; height: 16px; color: var(--cc-text-secondary); vertical-align: middle; margin-right: 4px;"></span>
                        <strong><?php esc_html_e( 'Filters', 'wp-content-curator' ); ?></strong>
                    </div>

                    <div class="toolbar-item">
                        <label for="cc-history-status-filter"><?php echo esc_html( $d['col_status'] ); ?></label>
                        <select id="cc-history-status-filter" name="status" onchange="this.form.submit();">
                            <option value="all" <?php selected( $status_filter, 'all' ); ?>><?php echo esc_html( $d['status_all_history'] ); ?></option>
                            <option value="processed" <?php selected( $status_filter, 'processed' ); ?>><?php echo esc_html( $d['status_processed'] ); ?></option>
                            <option value="ignored" <?php selected( $status_filter, 'ignored' ); ?>><?php echo esc_html( $d['status_ignored'] ); ?></option>
                        </select>
                    </div>

                    <div class="toolbar-item">
                        <label for="cc-history-site-filter"><?php echo esc_html( $d['site'] ); ?></label>
                        <select id="cc-history-site-filter" name="site" onchange="this.form.submit();">
                            <option value="all" <?php selected( $site_filter, 'all' ); ?>><?php echo esc_html( $d['todos'] ); ?></option>
                            <?php foreach ( $sites as $site_name ) : ?>
                                <option value="<?php echo esc_attr( $site_name ); ?>" <?php selected( $site_filter, $site_name ); ?>><?php echo esc_html( $site_name ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="toolbar-item">
                        <label for="cc-history-start-date"><?php echo esc_html( $d['from'] ); ?></label>
                        <input type="date" id="cc-history-start-date" name="start_date" value="<?php echo esc_attr( $start_date ); ?>" onchange="this.form.submit();" />
                    </div>

                    <div class="toolbar-item">
                        <label for="cc-history-end-date"><?php echo esc_html( $d['to'] ); ?></label>
                        <input type="date" id="cc-history-end-date" name="end_date" value="<?php echo esc_attr( $end_date ); ?>" onchange="this.form.submit();" />
                    </div>

                    <?php if ( $filters_active ) : ?>
                        <a href="<?php echo esc_url( $base_url ); ?>" class="button button-secondary clear-filters-btn" style="margin-left: 10px;">
                            <?php echo esc_html( $d['clear_filters'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Notification area -->
            <div id="cc-history-notices" class="content-curator-notices" style="display: none;"></div>

            <?php if ( empty( $posts ) ) : ?>
                <div class="content-curator-empty">
                    <span class="dashicons dashicons-backup" style="font-size: 48px; width: 48px; height: 48px; color: #c3c4c7;"></span>
                    <h2><?php echo esc_html( $d['no_history'] ); ?></h2>
                    <p><?php echo esc_html( $d['no_history_desc'] ); ?></p>
                </div>
            <?php else : ?>
                <!-- History Table -->
                <div class="cc-history-table-wrap">
                    <table class="wp-list-table widefat fixed striped cc-history-table">
                        <thead>
                            <tr>
                                <th class="col-id"><?php echo esc_html( $d['col_id'] ); ?></th>
                                <th class="col-page"><?php echo esc_html( $d['col_page'] ); ?></th>
                                <th class="col-preview"><?php echo esc_html( $d['col_preview'] ); ?></th>
                                <th class="col-status"><?php echo esc_html( $d['col_status'] ); ?></th>
                                <th class="col-fb-date"><?php echo esc_html( $d['col_fb_date'] ); ?></th>
                                <th class="col-fetched"><?php echo esc_html( $d['col_fetched'] ); ?></th>
                                <th class="col-actions"><?php echo esc_html( $d['col_actions'] ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $posts as $post ) :
                                $preview_text = mb_strimwidth( $post->original_text, 0, 140, '…' );
                                $status_class = 'processed' === $post->status ? 'cc-badge-processed' : 'cc-badge-ignored';
                                $status_label = 'processed' === $post->status ? $d['status_processed'] : $d['status_ignored'];
                            ?>
                                <tr class="cc-history-row" id="cc-history-row-<?php echo absint( $post->id ); ?>" data-post-id="<?php echo absint( $post->id ); ?>">
                                    <td class="col-id"><?php echo absint( $post->id ); ?></td>
                                    <td class="col-page">
                                        <span class="dashicons dashicons-facebook" style="font-size: 13px; width: 13px; height: 13px; vertical-align: middle; color: #1877f2; margin-right: 4px;"></span>
                                        <?php echo esc_html( $post->page_name ); ?>
                                    </td>
                                    <td class="col-preview">
                                        <div class="cc-history-preview-cell">
                                            <span class="cc-preview-short"><?php echo esc_html( $preview_text ); ?></span>
                                            <?php if ( mb_strlen( $post->original_text ) > 140 ) : ?>
                                                <button type="button"
                                                    class="button-link cc-preview-expand-btn"
                                                    data-full-text="<?php echo esc_attr( $post->original_text ); ?>"
                                                    data-page="<?php echo esc_attr( $post->page_name ); ?>"
                                                    title="<?php echo esc_attr( $d['history_view_text'] ); ?>">
                                                    <span class="dashicons dashicons-visibility" style="font-size: 14px; width: 14px; height: 14px; vertical-align: middle;"></span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="col-status">
                                        <span class="cc-status-badge <?php echo esc_attr( $status_class ); ?>">
                                            <?php echo esc_html( $status_label ); ?>
                                        </span>
                                    </td>
                                    <td class="col-fb-date"><?php echo esc_html( wp_date( 'M j, Y', strtotime( $post->fb_created_at ) ) ); ?></td>
                                    <td class="col-fetched"><?php echo esc_html( wp_date( 'M j, Y', strtotime( $post->fetched_at ) ) ); ?></td>
                                    <td class="col-actions">
                                        <button type="button"
                                            class="button button-small cc-history-requeue-btn"
                                            data-post-id="<?php echo absint( $post->id ); ?>"
                                            title="<?php echo esc_attr( $d['history_mark_pending'] ); ?>">
                                            <span class="dashicons dashicons-undo" style="font-size: 13px; width: 13px; height: 13px; vertical-align: middle;"></span>
                                            <?php echo esc_html( $d['history_mark_pending'] ); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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

        <!-- Full-text Preview Modal -->
        <div id="cc-history-modal-overlay" class="cc-modal-overlay" style="display: none;">
            <div class="cc-modal">
                <div class="cc-modal-header">
                    <span class="dashicons dashicons-facebook" style="color: #1877f2; margin-right: 6px; vertical-align: middle;"></span>
                    <strong id="cc-modal-page-name"></strong>
                    <button type="button" class="cc-modal-close" id="cc-modal-close-btn" title="<?php echo esc_attr( $d['modal_close'] ); ?>">
                        <span class="dashicons dashicons-no-alt"></span>
                    </button>
                </div>
                <div class="cc-modal-body">
                    <pre id="cc-modal-text-content"></pre>
                </div>
            </div>
        </div>
        <?php
    }

    // =========================================================================
    // HISTORY AJAX HANDLERS
    // =========================================================================

    /**
     * AJAX: Export history posts as a CSV file.
     *
     * @return void Outputs CSV and dies.
     */
    public function ajax_export_history() {
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $status     = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'all';
        $site       = isset( $_POST['site'] ) ? sanitize_text_field( wp_unslash( $_POST['site'] ) ) : 'all';
        $start_date = isset( $_POST['start_date'] ) ? sanitize_text_field( wp_unslash( $_POST['start_date'] ) ) : '';
        $end_date   = isset( $_POST['end_date'] ) ? sanitize_text_field( wp_unslash( $_POST['end_date'] ) ) : '';

        $posts = Content_Curator_DB::get_history_posts_for_export( $status, $site, $start_date, $end_date );

        $filename = 'content-curator-history-' . gmdate( 'Y-m-d' ) . '.csv';

        // Clean any output buffer.
        if ( ob_get_level() ) {
            ob_end_clean();
        }

        header( 'Content-Type: text/csv; charset=UTF-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        header( 'Pragma: no-cache' );
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Expires: 0' );

        // UTF-8 BOM so Excel recognises encoding correctly.
        echo "\xEF\xBB\xBF";

        /**
         * Use semicolon as separator — standard for Excel in Spanish/European locale.
         * Sanitise each text field: strip HTML tags and collapse internal newlines/tabs
         * to a single space so they don't create phantom rows inside Excel cells.
         */
        $sep = ';';

        $clean = function ( $value ) {
            // Strip HTML tags, collapse whitespace/newlines to a single space.
            $value = wp_strip_all_tags( (string) $value );
            $value = preg_replace( '/[\r\n\t]+/', ' ', $value );
            $value = preg_replace( '/\s{2,}/', ' ', $value );
            return trim( $value );
        };

        // Header row.
        $headers = array( 'ID', 'FB Post ID', 'Facebook Page', 'Status', 'Original Text', 'FB Created At', 'Fetched At' );
        echo implode( $sep, array_map( function( $h ) use ( $sep ) {
            // Quote header if it contains the separator.
            return strpos( $h, $sep ) !== false ? '"' . $h . '"' : $h;
        }, $headers ) ) . "\r\n";

        // Data rows.
        foreach ( $posts as $post ) {
            $row = array(
                (int) $post->id,
                $clean( $post->fb_post_id ),
                $clean( $post->page_name ),
                $clean( $post->status ),
                $clean( $post->original_text ),
                $clean( $post->fb_created_at ),
                $clean( $post->fetched_at ),
            );

            $escaped = array();
            foreach ( $row as $cell ) {
                $cell = (string) $cell;
                // If cell contains separator, double-quotes, or line breaks → wrap in double quotes.
                if ( strpos( $cell, $sep ) !== false || strpos( $cell, '"' ) !== false ) {
                    $cell = '"' . str_replace( '"', '""', $cell ) . '"';
                }
                $escaped[] = $cell;
            }

            echo implode( $sep, $escaped ) . "\r\n";
        }

        exit;
    }

    /**
     * AJAX: Update the status of a history record (e.g. re-queue as pending).
     *
     * Expected POST params: post_id, new_status, nonce.
     *
     * @return void Sends JSON response and dies.
     */
    public function ajax_history_update_status() {
        if ( ! check_ajax_referer( 'content_curator_nonce', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'wp-content-curator' ) ), 403 );
        }

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Insufficient permissions.', 'wp-content-curator' ) ), 403 );
        }

        $post_id    = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        $new_status = isset( $_POST['new_status'] ) ? sanitize_text_field( wp_unslash( $_POST['new_status'] ) ) : '';

        if ( ! $post_id ) {
            wp_send_json_error( array( 'message' => __( 'Invalid post ID.', 'wp-content-curator' ) ) );
        }

        $updated = Content_Curator_DB::update_status( $post_id, $new_status );

        if ( ! $updated ) {
            wp_send_json_error( array( 'message' => __( 'Failed to update post status.', 'wp-content-curator' ) ) );
        }

        wp_send_json_success( array(
            'message' => __( 'Status updated successfully.', 'wp-content-curator' ),
            'post_id' => $post_id,
        ) );
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
     * @param string $custom_filename Optional custom filename (without extension).
     * @return int|WP_Error The attachment ID on success, WP_Error on failure.
     */
    public static function sideload_image( $image_url, $post_id, $custom_filename = '' ) {
        // Include required WordPress media handling files.
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        // Download the image to a temporary file.
        $tmp_file = download_url( $image_url, 30 );

        if ( is_wp_error( $tmp_file ) ) {
            return $tmp_file;
        }

        // Determine a filename — Facebook URLs may not have a clean extension.
        $filename = basename( wp_parse_url( $image_url, PHP_URL_PATH ) );
        $ext = '';
        if ( preg_match( '/\.(jpe?g|png|gif|webp)$/i', $filename, $matches ) ) {
            $ext = strtolower( $matches[1] );
        } else {
            // Detect MIME type from the downloaded file and assign extension.
            $mime = wp_check_filetype_and_ext( $tmp_file, $filename );
            $ext  = $mime['ext'] ? $mime['ext'] : 'jpg';
        }

        if ( ! empty( $custom_filename ) ) {
            $filename = sanitize_title( $custom_filename ) . '.' . $ext;
        } else if ( ! preg_match( '/\.(jpe?g|png|gif|webp)$/i', $filename ) ) {
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
