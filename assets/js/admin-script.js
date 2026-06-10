/**
 * WP Facebook Curator - Admin Panel JavaScript
 *
 * Handles all AJAX interactions for the curation dashboard:
 * - AI text rewriting
 * - Draft/Publish post creation
 * - Manual fetch trigger
 *
 * @package WP_Facebook_Curator
 */

(function ($) {
    'use strict';

    // Bail early if the localized data object is not available.
    if (typeof contentCuratorData === 'undefined') {
        return;
    }

    var ajaxUrl = contentCuratorData.ajax_url;
    var nonce   = contentCuratorData.nonce;
    var strings = contentCuratorData.strings;

    // =========================================================================
    // UTILITY FUNCTIONS
    // =========================================================================

    /**
     * Show a notification banner in the notices area.
     *
     * @param {string} message The message to display.
     * @param {string} type    'success' or 'error'.
     */
    function showNotice(message, type) {
        var $container = $('#content-curator-notices');
        var icon = type === 'success' ? 'dashicons-yes-alt' : 'dashicons-warning';
        var html = '<div class="content-curator-notice notice-' + type + '">' +
                   '<span class="dashicons ' + icon + '"></span>' +
                   '<span>' + message + '</span>' +
                   '</div>';

        $container.show().prepend(html);

        // Auto-dismiss after 6 seconds.
        setTimeout(function () {
            $container.find('.content-curator-notice').first().fadeOut(300, function () {
                $(this).remove();
                if ($container.children().length === 0) {
                    $container.hide();
                }
            });
        }, 6000);
    }

    /**
     * Show the loading overlay on a specific card.
     *
     * @param {jQuery} $card      The card element.
     * @param {string} loadingText Text to show on the overlay.
     */
    function showCardLoading($card, loadingText) {
        $card.find('.card-loading-text').text(loadingText);
        $card.find('.card-loading').fadeIn(150);
        $card.find('.card-actions .button, .card-editor-actions .button').prop('disabled', true);
    }

    /**
     * Hide the loading overlay on a specific card.
     *
     * @param {jQuery} $card The card element.
     */
    function hideCardLoading($card) {
        $card.find('.card-loading').fadeOut(150);
        $card.find('.card-actions .button, .card-editor-actions .button').prop('disabled', false);
    }

    /**
     * Fade out and remove a card after successful publish/draft.
     *
     * @param {jQuery} $card The card element.
     */
    function removeCard($card) {
        $card.addClass('is-fading');
        setTimeout(function () {
            $card.slideUp(300, function () {
                $(this).remove();
                updatePostCount();
            });
        }, 400);
    }

    /**
     * Update the pending post count badge after card removal.
     */
    function updatePostCount() {
        var count = $('.content-curator-card').length;
        var text = count === 1 ? count + ' pending post' : count + ' pending posts';
        $('.content-curator-count').text(text);

        // Show empty state if no cards remain.
        if (count === 0) {
            $('.content-curator-grid').replaceWith(
                '<div class="content-curator-empty">' +
                '<span class="dashicons dashicons-yes-alt" style="font-size: 48px; width: 48px; height: 48px; color: #2e7d32;"></span>' +
                '<h2>All posts have been processed!</h2>' +
                '<p>Great job! Check back later for new content.</p>' +
                '</div>'
            );
        }
    }

    // =========================================================================
    // EVENT: TABS FOR MULTI-LANGUAGE EDITOR
    // =========================================================================
    $(document).on('click', '.editor-tab-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var postId = $btn.data('post-id');
        var lang = $btn.data('lang');
        var $card = $btn.closest('.content-curator-card');

        // Toggle active button
        $card.find('.editor-tabs[data-post-id="' + postId + '"] .editor-tab-btn').removeClass('active');
        $btn.addClass('active');

        // Show/hide corresponding textarea
        $card.find('.content-curator-textarea[data-post-id="' + postId + '"]').hide().removeClass('active');
        $card.find('.content-curator-textarea[data-post-id="' + postId + '"][data-lang="' + lang + '"]').show().addClass('active');
    });

    // =========================================================================
    // EVENT: TOGGLE EVENT FIELDS ON POST TYPE CHANGE
    // =========================================================================
    $(document).on('change', '.select-post-type', function () {
        var $select = $(this);
        var $card = $select.closest('.content-curator-card');
        var val = $select.val();
        if (val === 'agenda') {
            $card.find('.agenda-only-fields').slideDown(200);
        } else {
            $card.find('.agenda-only-fields').slideUp(200);
        }
    });

    // Trigger on ready to initialize visible fields for pre-selected post types
    $(function () {
        $('.select-post-type').trigger('change');
    });

    // =========================================================================
    // EVENT: AI REWRITE
    // =========================================================================

    $(document).on('click', '.btn-ai', function (e) {
        e.preventDefault();

        var $button = $(this);
        var $card   = $button.closest('.content-curator-card');
        var postId  = $button.data('post-id');
        
        // Use the currently active textarea's value, or the first one if none active.
        var $activeTextarea = $card.find('.content-curator-textarea.active');
        if ($activeTextarea.length === 0) {
            $activeTextarea = $card.find('.content-curator-textarea').first();
        }
        var text = $activeTextarea.val();

        if (!text || !text.trim()) {
            showNotice(strings.error_generic, 'error');
            return;
        }

        showCardLoading($card, strings.rewriting);

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action:  'content_curator_rewrite',
                nonce:   nonce,
                post_id: postId,
                text:    text
            },
            success: function (response) {
                hideCardLoading($card);

                if (response.success && (response.data.translations || response.data.rewritten_text)) {
                    if (response.data.translations) {
                        var translations = response.data.translations;
                        $card.find('.content-curator-textarea').each(function () {
                            var $txt = $(this);
                            var lang = $txt.data('lang');
                            if (translations[lang]) {
                                $txt.val(translations[lang]);
                                // Quick highlight animation on the textarea.
                                $txt.css('background-color', '#e8f5e9');
                                setTimeout(function () {
                                    $txt.css('background-color', '');
                                }, 1500);
                            }
                        });
                    } else if (response.data.rewritten_text) {
                        var $txt = $card.find('.content-curator-textarea');
                        $txt.val(response.data.rewritten_text);
                        $txt.css('background-color', '#e8f5e9');
                        setTimeout(function () {
                            $txt.css('background-color', '');
                        }, 1500);
                    }
                    showNotice(strings.success_rewrite, 'success');
                } else {
                    var msg = (response.data && response.data.message) ? response.data.message : strings.error_generic;
                    showNotice(msg, 'error');
                }
            },
            error: function () {
                hideCardLoading($card);
                showNotice(strings.error_generic, 'error');
            }
        });
    });

    // =========================================================================
    // EVENT: SAVE AS DRAFT / PUBLISH
    // =========================================================================

    $(document).on('click', '.btn-draft, .btn-publish', function (e) {
        e.preventDefault();

        var $button       = $(this);
        var $card         = $button.closest('.content-curator-card');
        var postId        = $button.data('post-id');
        var imageUrl      = $button.data('image-url') || '';
        var publishStatus = $button.data('status');

        // Gather the texts of all language tabs inside this card
        var texts = {};
        var hasContent = false;
        $card.find('.content-curator-textarea').each(function () {
            var $txt = $(this);
            var lang = $txt.data('lang');
            var val  = $txt.val();
            if (val && val.trim()) {
                texts[lang] = val;
                hasContent = true;
            }
        });

        var postType      = $card.find('.select-post-type').val() || 'post';
        var postTag       = $card.find('.select-post-tag').val() || '';

        // Event fields values
        var eventStartDate = $card.find('.event-start-date').val() || '';
        var eventEndDate   = $card.find('.event-end-date').val() || '';
        var eventLocation  = $card.find('.event-location').val() || '';
        var eventCoords    = $card.find('.event-coords').val() || '';
        var eventVideo     = $card.find('.event-video').val() || '';
        
        // Multi-select taxonomies values (joined as comma separated strings)
        var eventCategories = $card.find('.event-categories').val() || [];
        var eventConcellos  = $card.find('.event-concellos').val() || [];

        if (!hasContent) {
            showNotice('Post content cannot be empty.', 'error');
            return;
        }

        // Confirmation dialog.
        var confirmMsg = publishStatus === 'publish' ? strings.confirm_publish : strings.confirm_draft;
        if (!confirm(confirmMsg)) {
            return;
        }

        var loadingText = publishStatus === 'publish' ? strings.publishing : strings.saving_draft;
        showCardLoading($card, loadingText);

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action:         'content_curator_publish',
                nonce:          nonce,
                post_id:        postId,
                texts:          JSON.stringify(texts),
                text:           texts['en'] || Object.values(texts)[0] || '', // legacy fallback
                image_url:      imageUrl,
                publish_status: publishStatus,
                post_type:      postType,
                tag:            postTag,
                event_start_date: eventStartDate,
                event_end_date:   eventEndDate,
                event_location:   eventLocation,
                event_coords:     eventCoords,
                event_video:      eventVideo,
                event_categories: eventCategories.join(','),
                event_concellos:  eventConcellos.join(',')
            },
            success: function (response) {
                hideCardLoading($card);

                if (response.success) {
                    var msg = response.data.message || (publishStatus === 'publish' ? strings.success_publish : strings.success_draft);
                    showNotice(msg, 'success');
                    removeCard($card);
                } else {
                    var errMsg = (response.data && response.data.message) ? response.data.message : strings.error_generic;
                    showNotice(errMsg, 'error');
                }
            },
            error: function () {
                hideCardLoading($card);
                showNotice(strings.error_generic, 'error');
            }
        });
    });

    // =========================================================================
    // EVENT: DELETE POST
    // =========================================================================

    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();

        var $button = $(this);
        var $card   = $button.closest('.content-curator-card');
        var postId  = $button.data('post-id');

        // Confirmation dialog.
        if (!confirm(strings.confirm_delete)) {
            return;
        }

        showCardLoading($card, strings.deleting);

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action:  'content_curator_delete',
                nonce:   nonce,
                post_id: postId
            },
            success: function (response) {
                hideCardLoading($card);

                if (response.success) {
                    showNotice(response.data.message || strings.success_delete, 'success');
                    removeCard($card);
                } else {
                    var errMsg = (response.data && response.data.message) ? response.data.message : strings.error_generic;
                    showNotice(errMsg, 'error');
                }
            },
            error: function () {
                hideCardLoading($card);
                showNotice(strings.error_generic, 'error');
            }
        });
    });

    // =========================================================================
    // EVENT: DELETE ALL PENDING POSTS
    // =========================================================================

    $(document).on('click', '#content-curator-delete-all', function (e) {
        e.preventDefault();

        var $button = $(this);
        var $status = $('#content-curator-fetch-status');

        if (!confirm(strings.confirm_delete_all)) {
            return;
        }

        $button.prop('disabled', true);
        $status.text(strings.deleting_all)
               .removeClass('status-success status-error')
               .addClass('status-loading');

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'content_curator_delete_all',
                nonce:  nonce
            },
            success: function (response) {
                $button.prop('disabled', false);

                if (response.success) {
                    $status.text(response.data.message || strings.success_delete_all)
                           .removeClass('status-loading status-error')
                           .addClass('status-success');

                    // Reload page after 1.5s to show empty state
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else {
                    var msg = (response.data && response.data.message) ? response.data.message : strings.error_generic;
                    $status.text(msg)
                           .removeClass('status-loading status-success')
                           .addClass('status-error');
                }
            },
            error: function () {
                $button.prop('disabled', false);
                $status.text(strings.error_generic)
                       .removeClass('status-loading status-success')
                       .addClass('status-error');
            }
        });
    });

    // =========================================================================
    // EVENT: MANUAL FETCH NOW (Settings Page / Dashboard)
    // =========================================================================

    $(document).on('click', '#content-curator-fetch-now', function (e) {
        e.preventDefault();

        var $button = $(this);
        var $status = $('#content-curator-fetch-status');

        $button.prop('disabled', true);
        $status.text(strings.fetching)
               .removeClass('status-success status-error')
               .addClass('status-loading');

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'content_curator_fetch_now',
                nonce:  nonce
            },
            success: function (response) {
                $button.prop('disabled', false);

                if (response.success) {
                    $status.text(response.data.message)
                           .removeClass('status-loading status-error')
                           .addClass('status-success');

                    // If on the curation dashboard, reload the page after 1.5 seconds to show new posts
                    if ($('.content-curator-grid').length || $('.content-curator-empty').length) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }
                } else {
                    var msg = (response.data && response.data.message) ? response.data.message : strings.error_generic;
                    $status.text(msg)
                           .removeClass('status-loading status-success')
                           .addClass('status-error');
                }
            },
            error: function () {
                $button.prop('disabled', false);
                $status.text(strings.error_generic)
                       .removeClass('status-loading status-success')
                       .addClass('status-error');
            }
        });
    });

    /**
     * Cycle through images in a card's gallery.
     *
     * @param {HTMLElement} button    The clicked button element.
     * @param {number}      direction -1 for previous, 1 for next.
     */
    window.changeGalleryImage = function (button, direction) {
        var $container = $(button).closest('.card-image-gallery-container');
        var $slides    = $container.find('.card-gallery-image');
        var total      = $slides.length;
        if (total <= 1) {
            return;
        }

        var activeIndex = $slides.filter('.active').index();
        var newIndex    = activeIndex + direction;

        if (newIndex < 0) {
            newIndex = total - 1;
        } else if (newIndex >= total) {
            newIndex = 0;
        }

        $slides.removeClass('active').eq(newIndex).addClass('active');
        $container.find('.gallery-counter').text((newIndex + 1) + ' / ' + total);
    };

    $(document).on('change', '#content-curator-plugin-lang-select', function (e) {
        e.preventDefault();
        var selectedLang = $(this).val();

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'content_curator_change_plugin_lang',
                nonce:  nonce,
                lang:   selectedLang
            },
            success: function (response) {
                if (response.success) {
                    window.location.reload();
                }
            }
        });
    });

})(jQuery);
