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
            filter: '.section-content, .option-list',
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

        // Create section button handler
        $('#createSection').on('click', function() {
            createNewSection();
        });

        // Remove item handler
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.question-item').remove();
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
                $(this).closest('.form-section').remove();
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
                            ghostClass: 'sortable-ghost'
                        });
                    });
                    // Initialize nested sortable functionality
                    initializeNestedSortable($(item));
                }
                if ($(item).data('source') === 'available') {
                    initializeNewItem(item);
                }
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
                    scrollSpeed: 10
                });
            });
        }
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
                    scrollSpeed: 10
                });
            });
        }
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
            onAdd: function(evt) {
                const item = evt.item;
                if ($(item).data('source') === 'available') {
                    initializeNewItem(item);
                }
            }
        });
        
        // Focus on the section name input
        $template.find('.section-name-input').focus();
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
            onAdd: function(evt) {
                const item = evt.item;
                if ($(item).data('source') === 'available') {
                    initializeNewItem(item);
                }
            }
        });
        
        // Set collapsed state
        if (sectionData.is_expanded_by_default === false) {
            $sectionContent.addClass('collapsed');
            $template.find('.section-toggle i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
        
        return $template;
    }

    // Render the current structure
    function renderStructure(data) {
        const $canvas = $('#formCanvas').empty();
        
        // Data can be array of sections/items or legacy format
        if (!Array.isArray(data)) {
            data = [];
        }
        
        data.forEach((element) => {
            if (element.type === 'section') {
                // Render section
                const $section = renderSection(element);
                $canvas.append($section);
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
        const sections = [];
        const standaloneItems = [];
        
        // Process all elements in canvas (sections and standalone items)
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
                
                sections.push(sectionData);
            } else if ($element.hasClass('question-item')) {
                // This is a standalone item (not in a section)
                const item = serializeItem($element, standaloneItems.length, 0);
                standaloneItems.push(item);
            }
        });

        return {
            sections: sections,
            items: standaloneItems
        };
    }

    // Save the structure
    function saveStructure(data) {
        const $btn = $('#saveStructure').prop('disabled', true);
        const $icon = $btn.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');
        
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
                toastr.success('Form structure saved successfully');
                // Refresh the structure from server
                renderStructure(response.data.items);
            } else {
                console.error('Error saving structure:', response.message);
                toastr.error(response.message || 'Error saving structure');
            }
        })
        .fail(function(xhr, status, error) {
            console.error('Error saving structure:', status, error);
            toastr.error('Error saving structure');
        })
        .always(function() {
            $btn.prop('disabled', false);
            $icon.removeClass('fa-spinner fa-spin').addClass('fa-save');
        });
    }

})(jQuery);