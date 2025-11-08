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
            onClone: function(evt) {
                $(evt.clone).attr('data-source', 'available');
            }
        });

        // Initialize main form canvas
        Sortable.create(document.getElementById('formCanvas'), {
            group: {
                name: 'shared',
                pull: true,
                put: true
            },
            animation: 150,
            handle: '.handle',
            onAdd: function(evt) {
                const item = evt.item;
                if ($(item).data('source') === 'available') {
                    initializeNewItem(item);
                }
            }
        });

        // Load initial data
        loadStructure();

        // Save button handler
        $('#saveStructure').on('click', function() {
            const items = serializeStructure();
            saveStructure(items);
        });

        // Remove item handler
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.question-item').remove();
        });
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
        
        return new Sortable(el, {
            group: {
                name: 'shared',
                pull: true,
                put: true
            },
            animation: 150,
            handle: '.handle',
            ghostClass: 'sortable-ghost',
            onAdd: function(evt) {
                const item = evt.item;
                if ($(item).hasClass('question-item') && $(item).find('.option-containers').length) {
                    // Re-initialize sortables for any option containers in the moved item
                    $(item).find('.option-list').each(function() {
                        initSortable(this);
                    });
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
            console.error('Question not found:', questionId);
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

            // Init sortable for each option container
            $newItem.find('.option-list').each(function() {
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
            
            // Initialize sortable on the option-list immediately
            const $optionList = $container.find('.option-list');
            initSortable($optionList[0], {
                group: 'shared',
                animation: 150
            });
            
            return $container;
        });
    }

    // Render the current structure
    function renderStructure(items) {
        const $canvas = $('#formCanvas').empty();
        
        items.forEach(item => {
            const $item = $(renderQuestionItem(item.question));
            
            // If radio type with children, render option containers and their items
            if (item.question.type === 'radio' && item.question.options) {
                const $containers = renderOptionContainers(item.question.options);
                $item.find('.option-containers').empty().append($containers);

                // If there are children, add them to their respective option containers
                if (item.children) {
                    Object.entries(item.children).forEach(([optionValue, child]) => {
                        const $container = $item.find(`[data-option="${optionValue}"] .option-list`);
                        if ($container.length && child.items) {
                            child.items.forEach(childItem => {
                                const $childItem = $(renderQuestionItem(childItem.question));
                                $container.append($childItem);
                                
                                // If child is also a radio question, initialize its options
                                if (childItem.question.type === 'radio' && childItem.question.options) {
                                    const $childContainers = renderOptionContainers(childItem.question.options);
                                    $childItem.find('.option-containers').empty().append($childContainers);
                                }
                            });
                        }
                    });
                }
            }
            
            $canvas.append($item);
        });
    }

    // Serialize the current structure for saving
    function serializeStructure() {
        const items = [];
        
        $('#formCanvas > .question-item').each(function(index) {
            const item = {
                question_id: $(this).data('id'),
                order: index
            };

            // If radio type, serialize option containers
            const $containers = $(this).find('.option-container');
            if ($containers.length) {
                item.children = {};
                $containers.each(function() {
                    const optionValue = $(this).data('option');
                    const childItems = [];
                    
                    $(this).find('.option-list > .question-item').each(function(childIndex) {
                        childItems.push({
                            question_id: $(this).data('id'),
                            order: childIndex
                        });
                    });
                    
                    if (childItems.length) {
                        item.children[optionValue] = { items: childItems };
                    }
                });
            }

            items.push(item);
        });

        return items;
    }

    // Save the structure
    function saveStructure(items) {
        const $btn = $('#saveStructure').prop('disabled', true);
        const $icon = $btn.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');
        
        $.ajax({
            url: `${baseUrl}/admin/form-structure/${window.structureId}/save`,
            method: 'POST',
            data: { items },
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
                toastr.error(response.message || 'Error saving structure');
            }
        })
        .fail(function() {
            toastr.error('Error saving structure');
        })
        .always(function() {
            $btn.prop('disabled', false);
            $icon.removeClass('fa-spinner fa-spin').addClass('fa-save');
        });
    }

})(jQuery);