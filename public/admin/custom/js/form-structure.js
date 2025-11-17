// ============================================
// PREVIEW FUNCTION - DEFINED OUTSIDE CLOSURE
// ============================================
// Define showPreview OUTSIDE the closure so it's immediately available
window.showPreview = function() {
    // Wait for jQuery to be available
    if (typeof jQuery === 'undefined') {
        setTimeout(function() {
            window.showPreview();
        }, 100);
        return;
    }

    const $ = jQuery;

    // Check if required functions are available, if not wait
    if (typeof window.serializeStructure !== 'function') {
        setTimeout(function() {
            window.showPreview();
        }, 100);
        return;
    }

    // Get current structure from canvas
    let structureData;
    try {
        structureData = window.serializeStructure();
    } catch (e) {
        console.error('Error serializing structure:', e);
        structureData = { sections: [], items: [] };
    }

    // Show modal
    const $modal = $('#previewModal');
    const $container = $('#previewFormContainer');

    // Check if modal exists
    if ($modal.length === 0) {
        if (typeof toastr !== 'undefined') {
            toastr.error('Preview modal not found. Please refresh the page.');
        } else {
            alert('Preview modal not found. Please refresh the page.');
        }
        return;
    }

    // Clear and show loading
    $container.html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading preview...</p>
        </div>
    `);

    // Remove any existing backdrops first to prevent stacking
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').css('padding-right', '');

    // Try Bootstrap 5 Modal API first, fallback to jQuery
    try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Get or create modal instance
            let modalInstance = bootstrap.Modal.getInstance($modal[0]);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal($modal[0], {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
            }
            modalInstance.show();

            // Apply blur effect after modal is shown
            setTimeout(function() {
                applyModalBlur();
            }, 100);
        } else if (typeof $.fn.modal !== 'undefined') {
            $modal.modal('show');

            // Apply blur effect after modal is shown
            $modal.on('shown.bs.modal', function() {
                applyModalBlur();
            });
        } else {
            // Manual fallback
            $modal.css('display', 'block').addClass('show');
            $('body').addClass('modal-open').css('padding-right', '0px');
            if ($('.modal-backdrop').length === 0) {
                $('body').append('<div class="modal-backdrop fade show"></div>');
            }
            applyModalBlur();
        }
    } catch (e) {
        console.error('Error showing modal:', e);
        // Fallback: just show it with display
        $modal.css('display', 'block').addClass('show');
        $('body').addClass('modal-open');
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop fade show"></div>');
        }
        applyModalBlur();
    }

    // Function to apply blur effects to entire page (defined globally)
    window.applyModalBlur = function() {
        const $ = jQuery;

        // FIRST: Explicitly remove blur from modal BEFORE applying blur to other elements
        $('#previewModal, #previewModal *').css({
            'filter': 'none',
            '-webkit-filter': 'none',
            'backdrop-filter': 'none',
            '-webkit-backdrop-filter': 'none'
        });

        // Ensure backdrop has blur (but backdrop itself is not blurred)
        $('.modal-backdrop').css({
            'backdrop-filter': 'blur(10px)',
            '-webkit-backdrop-filter': 'blur(10px)',
            'background-color': 'rgba(0, 0, 0, 0.6)',
            'filter': 'none',
            '-webkit-filter': 'none'
        });

        // DON'T blur wrappers - blur only specific children to avoid affecting modal
        // Blur sidebar specifically (including zSidebar)
        $('.zSidebar, .zMain-wrap > .sidebar, .zMain-wrap > aside, .sidebar, aside').not('#previewModal').not('#previewModal *').css({
            'filter': 'blur(5px)',
            'pointer-events': 'none',
            'transition': 'filter 0.4s ease'
        });

        // Blur navigation/header inside zMainContent but NOT the modal
        $('.zMainContent > nav, .zMainContent > .navbar, .zMainContent > header').not('#previewModal').css({
            'filter': 'blur(5px)',
            'pointer-events': 'none',
            'transition': 'filter 0.4s ease'
        });

        // Blur content sections inside zMainContent but NOT the modal
        // Find all direct children of zMainContent except modal
        $('.zMainContent').children().not('#previewModal').not('.modal').not('.modal-backdrop').each(function() {
            if ($(this).attr('id') !== 'previewModal' && !$(this).hasClass('modal')) {
                $(this).css({
                    'filter': 'blur(5px)',
                    'pointer-events': 'none',
                    'transition': 'filter 0.4s ease'
                });
            }
        });

        // Also blur any nested content divs but exclude modal
        $('.zMainContent div, .zMainContent section').not('#previewModal').not('#previewModal *').not('.modal').not('.modal *').each(function() {
            // Skip if this element or any parent is the modal
            if (!$(this).closest('#previewModal').length && !$(this).is('#previewModal')) {
                $(this).css({
                    'filter': 'blur(5px)',
                    'pointer-events': 'none',
                    'transition': 'filter 0.4s ease'
                });
            }
        });

        // CRITICAL: Explicitly ensure modal is NOT blurred - use inline styles with !important
        function removeModalBlur() {
            // Use attr to set inline style with !important (jQuery can't do !important directly)
            $('#previewModal').each(function() {
                this.style.setProperty('filter', 'none', 'important');
                this.style.setProperty('-webkit-filter', 'none', 'important');
                this.style.setProperty('backdrop-filter', 'none', 'important');
                this.style.setProperty('-webkit-backdrop-filter', 'none', 'important');
            });

            // Apply to all children
            $('#previewModal *').each(function() {
                this.style.setProperty('filter', 'none', 'important');
                this.style.setProperty('-webkit-filter', 'none', 'important');
                this.style.setProperty('backdrop-filter', 'none', 'important');
                this.style.setProperty('-webkit-backdrop-filter', 'none', 'important');
            });

            // Also ensure modal dialog and content are not blurred
            $('.modal-dialog, .modal-content, .modal-header, .modal-body, .modal-footer').each(function() {
                this.style.setProperty('filter', 'none', 'important');
                this.style.setProperty('-webkit-filter', 'none', 'important');
                this.style.setProperty('backdrop-filter', 'none', 'important');
                this.style.setProperty('-webkit-backdrop-filter', 'none', 'important');
            });
        }

        // Apply immediately
        removeModalBlur();

        // Apply again after short delay
        setTimeout(removeModalBlur, 10);

        // Apply again after longer delay to ensure it sticks
        setTimeout(removeModalBlur, 100);
        setTimeout(removeModalBlur, 300);
    };

    // Function to remove blur effects from entire page (defined globally)
    window.removeModalBlur = function() {
        const $ = jQuery;

        // Remove blur from sidebar (including zSidebar)
        $('.zSidebar, .zMain-wrap > .sidebar, .zMain-wrap > aside, .sidebar, aside').css({
            'filter': '',
            'pointer-events': '',
            'transition': ''
        });

        // Remove blur from navigation/header
        $('.zMainContent > nav, .zMainContent > .navbar, .zMainContent > header').css({
            'filter': '',
            'pointer-events': '',
            'transition': ''
        });

        // Remove blur from all direct children of zMainContent
        $('.zMainContent').children().css({
            'filter': '',
            'pointer-events': '',
            'transition': ''
        });

        // Remove blur from all nested divs and sections
        $('.zMainContent div, .zMainContent section').css({
            'filter': '',
            'pointer-events': '',
            'transition': ''
        });

        // Remove blur from any other elements that might have been blurred
        $('body > *:not(.modal):not(.modal-backdrop)').css({
            'filter': '',
            'pointer-events': '',
            'transition': ''
        });

        // Also remove inline styles that were set with setProperty
        $('.zSidebar, .zMain-wrap, .sidebar, aside, .zMainContent, nav, .navbar, header').each(function() {
            this.style.removeProperty('filter');
            this.style.removeProperty('-webkit-filter');
            this.style.removeProperty('backdrop-filter');
            this.style.removeProperty('-webkit-backdrop-filter');
        });
    };

    // Render preview - wait for renderPreview to be available
    const renderPreviewWithRetry = function(attempts) {
        attempts = attempts || 0;
        if (typeof window.renderPreview === 'function') {
            window.renderPreview(structureData, $container);
        } else if (attempts < 50) {
            setTimeout(function() {
                renderPreviewWithRetry(attempts + 1);
            }, 100);
        } else {
            console.error('renderPreview function not available after waiting');
        }
    };
    renderPreviewWithRetry();
};

(function ($) {
    "use strict";

    // Global state
    let structure = null;
    let availableQuestions = [];

    // Get base URL from meta tag
    const baseUrl = $('meta[name="base-url"]').attr('content') || '';

    $(document).ready(function () {
        // First ensure SortableJS is loaded
        if (typeof Sortable === 'undefined') {
            console.error('SortableJS not loaded');
            return;
        }

        // Initialize Sortable for available questions (clone items on drag)
        Sortable.create(document.getElementById('availableQuestions'), {
            group: {
                name: 'shared',
                pull: 'clone',
                put: false
            },
            sort: false,
            handle: '.handle',
            animation: 150,
            // Auto-scroll options
            scroll: true,
            scrollSensitivity: 100,
            scrollSpeed: 10,
            // Specify scrollable container
            scrollFn: function(offsetX, offsetY, originalEvent, touchEvt, hoverTarget) {
                // Scroll the available questions container
                const container = document.getElementById('availableQuestions');
                const rect = container.getBoundingClientRect();
                const scrollTop = container.scrollTop;

                // Scroll up or down based on position
                if (offsetY < rect.top + 50) {
                    container.scrollTop = scrollTop - 10;
                } else if (offsetY > rect.bottom - 50) {
                    container.scrollTop = scrollTop + 10;
                }
            },
            onClone: function(evt) {
                $(evt.clone).attr('data-source', 'available');
            }
        });

        // Initialize main form canvas - allows sections and standalone items
        Sortable.create(document.getElementById('formCanvas'), {
            group: {
                name: 'shared',
                pull: true,
                put: true
            },
            animation: 150,
            handle: '.handle, .section-handle',
            filter: '.section-content, .option-list, .remove-item',
            // Auto-scroll options
            scroll: true,
            scrollSensitivity: 100,
            scrollSpeed: 10,
            // Specify scrollable container
            scrollFn: function(offsetX, offsetY, originalEvent, touchEvt, hoverTarget) {
                // Scroll the form canvas
                const canvas = document.getElementById('formCanvas');
                const rect = canvas.getBoundingClientRect();
                const scrollTop = canvas.scrollTop;

                // Scroll up or down based on position
                if (offsetY < rect.top + 50) {
                    canvas.scrollTop = scrollTop - 10;
                } else if (offsetY > rect.bottom - 50) {
                    canvas.scrollTop = scrollTop + 10;
                }
            },
            onAdd: function(evt) {
                const item = evt.item;
                // If it's a section, initialize its sortable
                if ($(item).hasClass('form-section')) {
                    const $sectionContent = $(item).find('.section-content');
                    if ($sectionContent.length && !$sectionContent.data('sortable-initialized')) {
                        initSortable($sectionContent[0], {
                            group: {
                                name: 'shared',
                                pull: true,
                                put: true
                            },
                            animation: 150,
                            handle: '.handle',
                            ghostClass: 'sortable-ghost',
                            scroll: true,
                            scrollSensitivity: 100,
                            scrollSpeed: 10,
            onAdd: function(evt) {
                const item = evt.item;
                if ($(item).data('source') === 'available') {
                                    initializeNewItem(item);
                                }
                            }
                        });
                        $sectionContent.data('sortable-initialized', true);
                    }
                } else if ($(item).data('source') === 'available') {
                    initializeNewItem(item);
                }
            }
        });

        // Load initial data
        loadStructure();

        // Save button handler
        $('#saveStructure').on('click', function() {
            const data = serializeStructure();
            saveStructure(data);
        });

        // Preview button handler - use delegated event for reliability
        $(document).on('click', '#previewForm', function(e) {
            e.preventDefault();
            if (typeof window.showPreview === 'function') {
                window.showPreview();
            }
        });

        // Create section button handler
        $('#createSection').on('click', function() {
            createNewSection();
        });

        // Remove item handler
        $(document).on('click', '.remove-item', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).closest('.question-item').fadeOut(300, function() {
                $(this).remove();
                updateSectionQuestionCounts();
                updateDepthLevels();
            });
        });

        // Section handlers
        $(document).on('click', '.section-toggle', function() {
            const $section = $(this).closest('.form-section');
            const $content = $section.find('.section-content');
            const $icon = $(this).find('i');

            $content.toggleClass('collapsed');
            $icon.toggleClass('fa-chevron-down fa-chevron-up');
        });

        $(document).on('click', '.section-remove', function() {
            if (confirm('Are you sure you want to remove this section? All questions in this section will also be removed.')) {
                const $section = $(this).closest('.form-section');
                $section.fadeOut(300, function() {
                    $(this).remove();
                    updateSectionQuestionCounts();
                });
            }
        });

        // Initialize section content areas as sortable
        initSectionSortables();
    });

    // Load structure and available questions
    function loadStructure() {
        $.get(`${baseUrl}/admin/form-structure/${window.structureId}`)
            .done(function(response) {
                if (response.status) {
                    structure = response.data.structure;
                    availableQuestions = response.data.available_questions;

                    // Render available questions
                    renderAvailableQuestions();

                    // Render current structure
                    renderStructure(response.data.items);
                    // Ensure counts and levels are updated after render
                    setTimeout(() => {
                        updateSectionQuestionCounts();
                        updateDepthLevels();
                    }, 200);
                }
            })
            .fail(function() {
                toastr.error('Error loading form structure');
            });
    }

    // Initialize Sortable for a container (used for option containers)
    function initSortable(element, options = {}) {
        const el = typeof element === 'string' ? document.querySelector(element) : element;
        if (!el) return null;

        // Get container info for logging
        const $container = $(el).closest('.option-container');
        const optionValue = $container.length ? $container.data('option') : 'root';
        const parentQuestionId = $container.closest('.question-item').data('id') || 'unknown';

        return new Sortable(el, {
            group: {
                name: 'shared',
                pull: true,
                put: true
            },
            animation: 150,
            handle: '.handle',
            ghostClass: 'sortable-ghost',
            // Auto-scroll options
            scroll: true,
            scrollSensitivity: 100,
            scrollSpeed: 10,
            // Specify scrollable container
            scrollFn: function(offsetX, offsetY, originalEvent, touchEvt, hoverTarget) {
                // Scroll the parent container
                const rect = el.getBoundingClientRect();
                const scrollTop = el.scrollTop;

                // Scroll up or down based on position
                if (offsetY < rect.top + 50) {
                    el.scrollTop = scrollTop - 10;
                } else if (offsetY > rect.bottom - 50) {
                    el.scrollTop = scrollTop + 10;
                }
            },
            onStart: function(evt) {
                $(evt.item).addClass('dragging');
                if (options.onStart) options.onStart(evt);
            },
            onEnd: function(evt) {
                $(evt.item).removeClass('dragging');
                $('.sortable-drag-over').removeClass('sortable-drag-over');
                if (options.onEnd) options.onEnd(evt);
            },
            onMove: function(evt) {
                $('.sortable-drag-over').removeClass('sortable-drag-over');
                if (evt.related && evt.related !== evt.dragged) {
                    $(evt.related).addClass('sortable-drag-over');
                }
                if (options.onMove) return options.onMove(evt);
                return true;
            },
            onAdd: function(evt) {
                const item = evt.item;
                if ($(item).hasClass('question-item') && $(item).find('.option-containers').length) {
                    // Re-initialize sortables for any option containers in the moved item
                    $(item).find('.option-list').each(function() {
                        initSortable(this, {
                            group: {
                                name: 'shared',
                                pull: true,
                                put: true
                            },
                            animation: 150,
                            handle: '.handle',
                            ghostClass: 'sortable-ghost',
                            onStart: function(evt) {
                                $(evt.item).addClass('dragging');
                            },
                            onEnd: function(evt) {
                                $(evt.item).removeClass('dragging');
                                $('.sortable-drag-over').removeClass('sortable-drag-over');
                                updateDepthLevels();
                            },
                            onMove: function(evt) {
                                $('.sortable-drag-over').removeClass('sortable-drag-over');
                                if (evt.related && evt.related !== evt.dragged) {
                                    $(evt.related).closest('.option-container').addClass('sortable-drag-over');
                                }
                                return true;
                            },
                            onAdd: function(evt) {
                                const item = evt.item;
                                if ($(item).data('source') === 'available') {
                                    initializeNewItem(item);
                                }
                                updateDepthLevels();
                            }
                        });
                    });
                    // Initialize nested sortable functionality
                    initializeNestedSortable($(item));
                }
                if ($(item).data('source') === 'available') {
                    initializeNewItem(item);
                }
                if (options.onAdd) options.onAdd(evt);
            },
            ...options
        });
    }

    // Initialize a newly added item
    function initializeNewItem(item) {
        const questionId = $(item).data('id');
        const question = window.questions.find(q => q.id === questionId) ||
                        availableQuestions.find(q => q.id === questionId);
        if (!question) {
            console.error('❌ Question not found:', questionId);
            $(item).remove();
            return;
        }

        // Replace clone with proper template
        const $newItem = $(renderQuestionItem(question));
        $(item).replaceWith($newItem);

        // Add fade-in animation
        $newItem.css('opacity', '0').animate({ opacity: 1 }, 300);

        // If it's a radio question, initialize option containers
        if (question.type === 'radio' && question.options) {
            const $containers = renderOptionContainers(question.options);
            $newItem.find('.option-containers').empty().append($containers);

            // Initialize sortable for each option container
            $newItem.find('.option-list').each(function() {
                initSortable(this, {
                    group: {
                        name: 'shared',
                        pull: true,
                        put: true
                    },
                    animation: 150,
                    handle: '.handle',
                    ghostClass: 'sortable-ghost',
                    // Auto-scroll options
                    scroll: true,
                    scrollSensitivity: 100,
                    scrollSpeed: 10,
                    onStart: function(evt) {
                        $(evt.item).addClass('dragging');
                    },
                    onEnd: function(evt) {
                        $(evt.item).removeClass('dragging');
                        $('.sortable-drag-over').removeClass('sortable-drag-over');
                        updateDepthLevels();
                    },
                    onMove: function(evt) {
                        $('.sortable-drag-over').removeClass('sortable-drag-over');
                        if (evt.related && evt.related !== evt.dragged) {
                            $(evt.related).closest('.option-container').addClass('sortable-drag-over');
                        }
                        return true;
                    },
                    onAdd: function(evt) {
                        const item = evt.item;
                        if ($(item).data('source') === 'available') {
                            initializeNewItem(item);
                        }
                        updateDepthLevels();
                    }
                });
            });
        }

        updateDepthLevels();
    }

    // Recursively initialize sortable functionality for nested items
    function initializeNestedSortable($item) {
        // If it's a radio question, initialize option containers
        const questionId = $item.data('id');
        const question = window.questions.find(q => q.id === questionId) ||
                        availableQuestions.find(q => q.id === questionId);

        if (question && question.type === 'radio' && question.options) {
            // Init sortable for each option container
            $item.find('.option-list').each(function() {
                initSortable(this, {
                    group: {
                        name: 'shared',
                        pull: true,
                        put: true
                    },
                    animation: 150,
                    handle: '.handle',
                    ghostClass: 'sortable-ghost',
                    // Auto-scroll options
                    scroll: true,
                    scrollSensitivity: 100,
                    scrollSpeed: 10,
                    onStart: function(evt) {
                        $(evt.item).addClass('dragging');
                    },
                    onEnd: function(evt) {
                        $(evt.item).removeClass('dragging');
                        $('.sortable-drag-over').removeClass('sortable-drag-over');
                        updateDepthLevels();
                    },
                    onMove: function(evt) {
                        $('.sortable-drag-over').removeClass('sortable-drag-over');
                        if (evt.related && evt.related !== evt.dragged) {
                            $(evt.related).closest('.option-container').addClass('sortable-drag-over');
                        }
                        return true;
                    },
                    onAdd: function(evt) {
                        const item = evt.item;
                        if ($(item).data('source') === 'available') {
                            initializeNewItem(item);
                        }
                        updateDepthLevels();
                    }
                });
            });
        }
    }

    // Update depth level indicators for nested option containers
    function updateDepthLevels() {
        $('.option-container').each(function() {
            let level = 0;
            let $current = $(this);
            // Count how many parent option-containers exist
            while ($current.length) {
                $current = $current.parent().closest('.option-container');
                if ($current.length) {
                    level++;
                } else {
                    break;
                }
            }
            $(this).attr('data-level', level);
        });
    }

    // Update question count badges in sections
    function updateSectionQuestionCounts() {
        $('.form-section').each(function() {
            const $section = $(this);
            const $content = $section.find('.section-content');
            // Count only top-level question items directly in section content
            const count = $content.children('.question-item').length;
            let $badge = $section.find('.section-question-count');

            if (count > 0) {
                if ($badge.length === 0) {
                    $badge = $('<span class="section-question-count"></span>');
                    $section.find('.section-title').append($badge);
                }
                $badge.text(count + ' question' + (count !== 1 ? 's' : ''));
            } else {
                $badge.remove();
            }
        });
    }

    // Render the list of available questions
    function renderAvailableQuestions() {
        const $list = $('#availableQuestions').empty();

        availableQuestions.forEach(question => {
            const $item = $(
                `<div class="question-item d-flex align-items-start"
                      data-id="${question.id}"
                      data-source="available">
                    <span class="handle me-2">
                        <i class="fa-solid fa-grip-lines"></i>
                    </span>
                    <div>
                        <div>${question.question}</div>
                        <div class="text-muted small">${question.type}</div>
                    </div>
                </div>`
            );
            $list.append($item);
        });
    }

    // Render a question item from template
    function renderQuestionItem(question) {
        const $template = $($('#questionItemTemplate').html());
        $template.attr('data-id', question.id);
        $template.find('.question-text').text(question.question);
        $template.find('.question-type').text(question.type);
        return $template;
    }

    // Render option containers for radio questions
    function renderOptionContainers(options) {
        if (!Array.isArray(options)) return $();

        const containers = options.map(option => {
            const value = typeof option === 'object' ? option.value : option;
            const label = typeof option === 'object' ? option.label : option;

            const $container = $($('#optionContainerTemplate').html());
            $container.attr('data-option', value);
            $container.find('.option-value').text(label);

            return $container;
        });

        // Return all containers as a single jQuery object
        return $($.map(containers, function(container) {
            return container.get();
        }));
    }

    // Create a new section
    function createNewSection() {
        const $template = $($('#sectionTemplate').html());
        const $canvas = $('#formCanvas');
        $canvas.append($template);

        // Initialize sortable for the section content
        const $sectionContent = $template.find('.section-content');
        initSortable($sectionContent[0], {
            group: {
                name: 'shared',
                pull: true,
                put: true
            },
            animation: 150,
            handle: '.handle',
            ghostClass: 'sortable-ghost',
            scroll: true,
            scrollSensitivity: 100,
            scrollSpeed: 10,
            onStart: function(evt) {
                $(evt.item).addClass('dragging');
            },
            onEnd: function(evt) {
                $(evt.item).removeClass('dragging');
                $('.sortable-drag-over').removeClass('sortable-drag-over');
                updateSectionQuestionCounts();
                updateDepthLevels();
            },
            onMove: function(evt) {
                $('.sortable-drag-over').removeClass('sortable-drag-over');
                if (evt.related && evt.related !== evt.dragged) {
                    $(evt.related).addClass('sortable-drag-over');
                }
                return true;
            },
            onAdd: function(evt) {
                const item = evt.item;
                if ($(item).data('source') === 'available') {
                    initializeNewItem(item);
                }
                updateSectionQuestionCounts();
                updateDepthLevels();
            }
        });

        // Focus on the section name input
        $template.find('.section-name-input').focus();
        updateSectionQuestionCounts();
    }

    // Initialize sortables for all section content areas
    function initSectionSortables() {
        $('.section-content').each(function() {
            if (!$(this).data('sortable-initialized')) {
                initSortable(this, {
                    group: {
                        name: 'shared',
                        pull: true,
                        put: true
                    },
                    animation: 150,
                    handle: '.handle',
                    ghostClass: 'sortable-ghost',
                    scroll: true,
                    scrollSensitivity: 100,
                    scrollSpeed: 10,
                    onAdd: function(evt) {
                        const item = evt.item;
                        if ($(item).data('source') === 'available') {
                            initializeNewItem(item);
                        }
                        updateSectionQuestionCounts();
                        updateDepthLevels();
                    }
                });
                $(this).data('sortable-initialized', true);
            }
        });
    }

    // Render a section
    function renderSection(sectionData) {
        const $template = $($('#sectionTemplate').html());
        $template.attr('data-section-id', sectionData.id || '');
        $template.find('.section-name-input').val(sectionData.name || '');

        const $sectionContent = $template.find('.section-content');

        // Render items in this section
        if (sectionData.items && sectionData.items.length > 0) {
            sectionData.items.forEach((item) => {
                const $item = $(renderQuestionItem(item.question));
                renderNestedItems($item, item, 0);
                $sectionContent.append($item);
            });
        }

        // Initialize sortable for section content
        initSortable($sectionContent[0], {
            group: {
                name: 'shared',
                pull: true,
                put: true
            },
            animation: 150,
            handle: '.handle',
            ghostClass: 'sortable-ghost',
            scroll: true,
            scrollSensitivity: 100,
            scrollSpeed: 10,
            onStart: function(evt) {
                $(evt.item).addClass('dragging');
            },
            onEnd: function(evt) {
                $(evt.item).removeClass('dragging');
                $('.sortable-drag-over').removeClass('sortable-drag-over');
                updateSectionQuestionCounts();
                updateDepthLevels();
            },
            onMove: function(evt) {
                $('.sortable-drag-over').removeClass('sortable-drag-over');
                if (evt.related && evt.related !== evt.dragged) {
                    $(evt.related).addClass('sortable-drag-over');
                }
                return true;
            },
            onAdd: function(evt) {
                const item = evt.item;
                if ($(item).data('source') === 'available') {
                    initializeNewItem(item);
                }
                updateSectionQuestionCounts();
                updateDepthLevels();
            }
        });

        // Set collapsed state
        if (sectionData.is_expanded_by_default === false) {
            $sectionContent.addClass('collapsed');
            $template.find('.section-toggle i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }

        // Update question count for this section
        setTimeout(() => {
            updateSectionQuestionCounts();
        }, 100);

        return $template;
    }

    // Render the current structure
    function renderStructure(data) {
        const $canvas = $('#formCanvas').empty();

        // Data can be array of sections/items or legacy format
        if (!Array.isArray(data)) {
            data = [];
        }

        // Sort by order if available to ensure correct order
        data.sort((a, b) => {
            const orderA = a.order !== undefined ? a.order : (a.type === 'section' ? 0 : 999);
            const orderB = b.order !== undefined ? b.order : (b.type === 'section' ? 0 : 999);
            return orderA - orderB;
        });

        data.forEach((element) => {
            if (element.type === 'section') {
                // Render section
                const $section = renderSection(element);
                $canvas.append($section);
            } else if (element.type === 'item' && element.item) {
                // Render standalone item (new format)
                const $item = $(renderQuestionItem(element.item.question));
                renderNestedItems($item, element.item, 0);
                $canvas.append($item);
            } else if (element.type === 'items' || element.items) {
                // Render standalone items (legacy format or items without section)
                const items = element.items || [];
                items.forEach((item) => {
            const $item = $(renderQuestionItem(item.question));
                    renderNestedItems($item, item, 0);
            $canvas.append($item);
        });
            } else if (element.question) {
                // Legacy format - single item
                const $item = $(renderQuestionItem(element.question));
                renderNestedItems($item, element, 0);
                $canvas.append($item);
            }
        });

        // Initialize section sortables
        initSectionSortables();

        // Update depth levels and question counts
        updateDepthLevels();
        updateSectionQuestionCounts();
    }

    // Recursively render nested items for radio questions
    function renderNestedItems($item, item, depth = 0) {
        // If radio type with children, render option containers and their items
        if (item.question && item.question.type === 'radio' && item.question.options) {
            const $containers = renderOptionContainers(item.question.options);
            $item.find('.option-containers').empty().append($containers);

            // Initialize sortable for each option container
            $item.find('.option-list').each(function() {
                initSortable(this, {
                    group: {
                        name: 'shared',
                        pull: true,
                        put: true
                    },
                    animation: 150,
                    handle: '.handle',
                    ghostClass: 'sortable-ghost',
                    // Auto-scroll options
                    scroll: true,
                    scrollSensitivity: 100,
                    scrollSpeed: 10
                });
            });

            // If there are children, add them to their respective option containers
            if (item.children) {
                // Get all available option containers with their values
                const availableOptions = {};
                $item.find('.option-container').each(function() {
                    const optionValue = $(this).data('option');
                    availableOptions[optionValue] = $(this).find('.option-list');
                });

                Object.entries(item.children).forEach(([optionValue, child]) => {
                    // Try exact match first
                    let $container = availableOptions[optionValue];

                    // If not found, try case-insensitive match
                    if (!$container || $container.length === 0) {
                        const matchingKey = Object.keys(availableOptions).find(key =>
                            key.toLowerCase() === optionValue.toLowerCase()
                        );
                        if (matchingKey) {
                            $container = availableOptions[matchingKey];
                        }
                    }

                    if ($container && $container.length > 0 && child.items && child.items.length > 0) {
                        child.items.forEach((childItem) => {
                            const $childItem = $(renderQuestionItem(childItem.question));
                            $container.append($childItem);

                            // Recursively render nested items for this child
                            renderNestedItems($childItem, childItem, depth + 1);
                        });
                    }
                });
            }
        }

        // Update depth levels after rendering nested items
        updateDepthLevels();
    }

    // Recursively serialize a question item and its nested children
    function serializeItem($item, order, depth = 0) {
        const questionId = $item.data('id');

            const item = {
            question_id: questionId,
            order: order
        };

        // Check if this is a radio question with option containers
        // IMPORTANT: Only look for option containers that are DIRECT children of THIS item's structure
        // Use .children() chain to avoid finding nested option containers from child items
        const $flexGrow = $item.children('.flex-grow-1');
        if ($flexGrow.length) {
            const $optionContainers = $flexGrow.children('.option-containers').children('.option-container');

            if ($optionContainers.length) {
                item.children = {};

                $optionContainers.each(function() {
                    const $container = $(this);
                    const optionValue = $container.data('option');
                    const childItems = [];

                    // Get direct children of .option-list (only immediate .question-item children)
                    const $optionList = $container.children('.option-list');
                    if ($optionList.length) {
                        const $directChildren = $optionList.children('.question-item');

                        $directChildren.each(function(childIndex) {
                            // Recursively serialize this child item
                            // This will handle nested option containers within the child
                            const childItem = serializeItem($(this), childIndex, depth + 1);
                            childItems.push(childItem);
                        });
                    }

                    if (childItems.length) {
                        item.children[optionValue] = { items: childItems };
                    }
                });
            }
        }

        return item;
    }

    // Serialize the current structure for saving
    function serializeStructure() {
        const orderedElements = [];

        // Process all elements in canvas (sections and standalone items) - preserve order
        $('#formCanvas > *').each(function(index) {
            const $element = $(this);

            if ($element.hasClass('form-section')) {
                // This is a section
                const sectionName = $element.find('.section-name-input').val().trim();
                if (!sectionName) {
                    // Skip sections without names
                    return;
                }

                const sectionData = {
                    type: 'section',
                    order: index,
                    name: sectionName,
                    description: null,
                    is_collapsible: true,
                    is_expanded_by_default: !$element.find('.section-content').hasClass('collapsed'),
                    items: []
                };

                // Serialize items in this section
                const $sectionContent = $element.find('.section-content');
                $sectionContent.children('.question-item').each(function(itemIndex) {
                    const item = serializeItem($(this), itemIndex, 0);
                    sectionData.items.push(item);
                });

                orderedElements.push(sectionData);
            } else if ($element.hasClass('question-item')) {
                // This is a standalone item (not in a section)
                const item = serializeItem($element, 0, 0);
                orderedElements.push({
                    type: 'item',
                    order: index,
                    item: item
                });
            }
        });

        return {
            elements: orderedElements
        };
    }

    // Expose serializeStructure globally AFTER it's defined
    window.serializeStructure = serializeStructure;

    // Save the structure
    function saveStructure(data) {
        const $btn = $('#saveStructure').prop('disabled', true);
        const $icon = $btn.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');
        const $canvas = $('#formCanvas');

        // Add loading overlay
        const $overlay = $('<div class="loading-overlay"><div class="spinner"></div></div>');
        $canvas.css('position', 'relative').append($overlay);

        $.ajax({
            url: `${baseUrl}/admin/form-structure/${window.structureId}/save`,
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function(response) {
            if (response.status) {
                // Success animation
                $('.form-section').addClass('saved');
                setTimeout(() => {
                    $('.form-section').removeClass('saved');
                }, 600);

                // Flash success
                $canvas.addClass('success-flash');
                setTimeout(() => {
                    $canvas.removeClass('success-flash');
                }, 600);

                toastr.success('Form structure saved successfully');

                // Refresh the structure from server
                renderStructure(response.data.items);

                // Update counts and depth levels
                updateSectionQuestionCounts();
                updateDepthLevels();
            } else {
                console.error('Error saving structure:', response.message);
                toastr.error(response.message || 'Error saving structure');

                // Shake animation on error
                $('.form-section').addClass('shake');
                setTimeout(() => {
                    $('.form-section').removeClass('shake');
                }, 500);
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Error saving structure:', status, error);
            toastr.error('Error saving structure');

            // Shake animation on error
            $('.form-section').addClass('shake');
            setTimeout(() => {
                $('.form-section').removeClass('shake');
            }, 500);
        })
        .always(function() {
            $btn.prop('disabled', false);
            $icon.removeClass('fa-spinner fa-spin').addClass('fa-save');
            $overlay.remove();
        });
    }

    // showPreview is now defined at the top of the file and exposed to window
    // This function definition is kept for backward compatibility but the global one is used

    // Render the preview form
    function renderPreview(structureData, $container) {
        // Check if new format (elements array) or old format (sections/items)
        const hasElements = structureData.elements && Array.isArray(structureData.elements) && structureData.elements.length > 0;
        const hasOldFormat = (structureData.sections && structureData.sections.length > 0) ||
                            (structureData.items && structureData.items.length > 0);

        if (!hasElements && !hasOldFormat) {
            $container.html(`
                <div class="preview-empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <h5>No form structure found</h5>
                    <p>Please add questions to the form structure to see the preview.</p>
                </div>
            `);
            return;
        }

        let html = '<form id="previewFormContent" class="preview-form">';

        if (hasElements) {
            // New format: elements array with mixed order
            structureData.elements.forEach((element) => {
                if (element.type === 'section') {
                    html += renderPreviewSection(element);
                } else if (element.type === 'item' && element.item) {
                    html += '<div class="preview-section">';
                    html += renderPreviewQuestion(element.item, 0);
                    html += '</div>';
                }
            });
        } else {
            // Old format: sections and items separately
            if (structureData.sections && structureData.sections.length > 0) {
                structureData.sections.forEach((section) => {
                    html += renderPreviewSection(section);
                });
            }

            if (structureData.items && structureData.items.length > 0) {
                html += '<div class="preview-section">';
                structureData.items.forEach((item) => {
                    html += renderPreviewQuestion(item, 0);
                });
                html += '</div>';
            }
        }

        html += '</form>';

        $container.html(html);

        // Initialize dynamic behavior for radio questions
        initializePreviewInteractivity();
    }

    // Render a preview section
    function renderPreviewSection(section) {
        let html = `<div class="preview-section">`;

        if (section.name) {
            html += `<h3 class="preview-section-title">${escapeHtml(section.name)}</h3>`;
        }

        if (section.description) {
            html += `<p class="preview-section-description">${escapeHtml(section.description)}</p>`;
        }

        if (section.items && section.items.length > 0) {
            section.items.forEach((item) => {
                html += renderPreviewQuestion(item, 0);
            });
        }

        html += `</div>`;
        return html;
    }

    // Render a preview question recursively
    function renderPreviewQuestion(item, depth = 0) {
        if (!item || !item.question_id) {
            return '';
        }

        const question = window.questions.find(q => q.id === item.question_id);
        if (!question) {
            return '';
        }

        // Use a stable ID for radio groups (same question = same ID)
        const questionId = `preview_q_${question.id}`;
        const required = question.required ? '<span class="required">*</span>' : '';
        const helpText = question.help_text ? `<div class="preview-question-help">${escapeHtml(question.help_text)}</div>` : '';

        let html = `<div class="preview-question" data-question-id="${question.id}" data-depth="${depth}">`;
        html += `<label class="preview-question-label">${escapeHtml(question.question)}${required}</label>`;
        html += helpText;

        // Render input based on type
        if (question.type === 'radio' && question.options && question.options.length > 0) {
            html += `<div class="preview-radio-group" data-question-id="${question.id}">`;
            question.options.forEach((option, index) => {
                const optionId = `${questionId}_opt_${index}`;
                const escapedOption = escapeHtml(option);
                html += `
                    <div class="preview-radio-option">
                        <input type="radio"
                               id="${optionId}"
                               name="${questionId}"
                               value="${escapedOption}"
                               data-question-id="${question.id}"
                               data-option-value="${escapedOption}">
                        <label for="${optionId}">${escapedOption}</label>
                    </div>
                `;
            });
            html += `</div>`;

            // Render nested questions for each option (hidden by default)
            if (item.children && typeof item.children === 'object') {
                Object.entries(item.children).forEach(([optionValue, childData]) => {
                    if (childData && childData.items && childData.items.length > 0) {
                        const nestedContainerId = `nested_${question.id}_${String(optionValue).replace(/[^a-zA-Z0-9]/g, '_')}`;
                        const escapedOptionValue = escapeHtml(String(optionValue));
                        html += `<div class="preview-nested-questions" id="${nestedContainerId}" data-parent-question="${question.id}" data-option-value="${escapedOptionValue}" style="display: none;">`;
                        childData.items.forEach((childItem) => {
                            html += renderPreviewQuestion(childItem, depth + 1);
                        });
                        html += `</div>`;
                    }
                });
            }
        } else if (question.type === 'text') {
            html += `<input type="text" class="preview-text-input" name="${questionId}" ${question.required ? 'required' : ''} placeholder="Enter your answer">`;
        } else if (question.type === 'textarea') {
            html += `<textarea class="preview-textarea" name="${questionId}" rows="4" ${question.required ? 'required' : ''} placeholder="Enter your answer"></textarea>`;
        } else if (question.type === 'number') {
            html += `<input type="number" class="preview-text-input" name="${questionId}" ${question.required ? 'required' : ''} placeholder="Enter a number">`;
        } else if (question.type === 'file') {
            html += `<input type="file" class="preview-text-input" name="${questionId}" ${question.required ? 'required' : ''} accept="*/*">`;
        } else if (question.type === 'select' && question.options && question.options.length > 0) {
            html += `<select class="preview-select-input" name="${questionId}" ${question.required ? 'required' : ''}>`;
            html += `<option value="">-- Select an option --</option>`;
            question.options.forEach((option) => {
                // Handle both object format {value, label} and string format
                let optionValue, optionLabel;
                if (typeof option === 'object' && option !== null) {
                    optionValue = option.value !== undefined ? option.value : option.label;
                    optionLabel = option.label !== undefined ? option.label : option.value;
                } else {
                    optionValue = option;
                    optionLabel = option;
                }
                html += `<option value="${escapeHtml(String(optionValue))}">${escapeHtml(String(optionLabel))}</option>`;
            });
            html += `</select>`;
        } else if (question.type === 'checkbox' && question.options && question.options.length > 0) {
            html += `<div class="preview-checkbox-group">`;
            question.options.forEach((option, index) => {
                const optionId = `${questionId}_opt_${index}`;
                let optionValue, optionLabel;
                if (typeof option === 'object' && option !== null) {
                    optionValue = option.value !== undefined ? option.value : option.label;
                    optionLabel = option.label !== undefined ? option.label : option.value;
                } else {
                    optionValue = option;
                    optionLabel = option;
                }
                const escapedOptionValue = escapeHtml(String(optionValue));
                const escapedOptionLabel = escapeHtml(String(optionLabel));
                html += `
                    <div class="preview-checkbox-option">
                        <input type="checkbox"
                               id="${optionId}"
                               name="${questionId}[]"
                               value="${escapedOptionValue}"
                               ${question.required ? 'required' : ''}>
                        <label for="${optionId}">${escapedOptionLabel}</label>
                    </div>
                `;
            });
            html += `</div>`;
        }

        html += `</div>`;
        return html;
    }

    // Initialize interactivity for preview (show/hide nested questions)
    function initializePreviewInteractivity() {
        // Remove any existing handlers to prevent duplicates
        $('#previewFormContainer').off('change', 'input[type="radio"]');

        // Handle radio button changes - standard behavior (no deselection)
        $('#previewFormContainer').on('change', 'input[type="radio"]', function(e) {
            e.stopPropagation();
            const $radio = $(this);
            const questionId = $radio.data('question-id');
            const optionValue = $radio.data('option-value');

            if (!questionId || !optionValue) {
                return;
            }

            // Hide all nested questions for this parent question
            const $allNested = $(`.preview-nested-questions[data-parent-question="${questionId}"]`);
            $allNested.removeClass('show').hide();

            // Show nested questions for selected option (case-insensitive match)
            const $nestedContainer = $allNested.filter(function() {
                const containerOptionValue = $(this).data('option-value');
                return containerOptionValue && containerOptionValue.toLowerCase() === optionValue.toLowerCase();
            });

            if ($nestedContainer.length > 0) {
                $nestedContainer.addClass('show').show();
            }
        });
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    // Expose renderPreview globally AFTER it's defined
    window.renderPreview = renderPreview;

})(jQuery);
