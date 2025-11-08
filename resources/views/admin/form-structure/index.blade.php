@extends('layouts.app')

@push('title')
    {{ $pageTitle ?? __('Questions Structure') }}
@endpush

@push('style')
    <style>
        /* Right side - Available questions with vertical scroll */
        #availableQuestions {
            min-height: 400px;
            max-height: 600px;
            overflow-y: auto;
            overflow-x: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
        }
        
        /* Left side - Form canvas with horizontal scroll (primary) and optional vertical scroll */
        #formCanvas {
            min-height: 400px;
            max-height: 600px;
            overflow-x: auto;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
            position: relative;
            width: 100%;
            box-sizing: border-box;
        }
        
        /* Top-level items in canvas - stack vertically */
        #formCanvas > .question-item {
            display: block;
            width: 100%;
            margin-bottom: 0.5rem;
            box-sizing: border-box;
        }
        
        /* Allow nested items to expand horizontally when needed */
        .question-item {
            box-sizing: border-box;
            white-space: normal;
        }
        
        /* Ensure option containers can expand with nested content horizontally */
        .option-container {
            box-sizing: border-box;
            white-space: normal;
        }
        
        /* Allow nested content to expand horizontally beyond container width */
        .question-item .flex-grow-1 {
            overflow: visible;
        }
        
        /* Ensure nested content can expand horizontally beyond container */
        .option-containers {
            display: block;
            overflow: visible;
        }
        
        /* Prevent wrapping that would hide horizontal expansion */
        .option-list {
            overflow: visible;
        }
        
        /* Ensure deeply nested items can expand naturally and push container width */
        .option-container .question-item {
            width: auto;
            min-width: 250px;
        }
        
        /* Top-level items should respect container width */
        #formCanvas > .question-item {
            width: 100%;
        }
        
        /* Sections in canvas */
        #formCanvas > .form-section {
            width: 100%;
            margin-bottom: 1rem;
            box-sizing: border-box;
        }
        
        /* Section content should allow questions to be dropped and expand */
        .section-content {
            min-height: 100px;
            min-width: 100%;
        }
        
        /* Legacy support for question-list class */
        .question-list {
            min-height: 400px;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
        }
        
        .question-item {
            cursor: move;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
        }
        .question-item:hover {
            border-color: #93c5fd;
        }
        .option-container {
            margin-left: 2rem;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 0.375rem;
        }
        .option-container .title {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        .option-list {
            min-height: 2rem;
        }
        .sortable-ghost {
            opacity: 0.5;
            background: #e2e8f0;
        }
        .handle {
            cursor: grab;
            color: #94a3b8;
            padding: 0 0.5rem;
        }
        .handle:active {
            cursor: grabbing;
        }
        
        /* Custom scrollbar styling for better UX */
        #availableQuestions::-webkit-scrollbar,
        #formCanvas::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        #availableQuestions::-webkit-scrollbar-track,
        #formCanvas::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        #availableQuestions::-webkit-scrollbar-thumb,
        #formCanvas::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        #availableQuestions::-webkit-scrollbar-thumb:hover,
        #formCanvas::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Section styles */
        .form-section {
            margin-bottom: 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #ffffff;
            overflow: visible;
            width: 100%;
            box-sizing: border-box;
        }
        
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: move;
        }
        
        .section-header:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
        
        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }
        
        .section-title input {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            font-weight: 600;
            flex: 1;
            max-width: 400px;
        }
        
        .section-title input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .section-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .section-content {
            padding: 1rem;
            min-height: 100px;
            overflow-x: auto;
            overflow-y: visible;
            width: 100%;
            box-sizing: border-box;
            position: relative;
        }
        
        .section-content.collapsed {
            display: none;
        }
        
        /* Top-level questions in section - allow full width */
        .section-content > .question-item {
            width: 100%;
            box-sizing: border-box;
        }
        
        /* Critical: Allow nested content to expand beyond section width */
        .section-content .question-item .flex-grow-1 {
            overflow: visible;
        }
        
        .section-content .option-containers {
            overflow: visible;
            display: block;
        }
        
        .section-content .option-container {
            box-sizing: border-box;
            white-space: normal;
        }
        
        /* Nested items inside option containers can expand horizontally */
        .section-content .option-container .question-item {
            width: auto;
            min-width: 250px;
            box-sizing: border-box;
        }
        
        /* Allow deeply nested content to expand */
        .section-content .question-item {
            white-space: normal;
        }
        
        .section-content .option-container .option-list {
            overflow: visible;
        }
        
        /* Ensure nested structures don't get constrained - allow natural expansion */
        .section-content .option-containers {
            width: auto;
            min-width: 0;
            display: block;
        }
        
        .section-content .option-container {
            width: auto;
            min-width: 0;
        }
        
        .section-content .option-list {
            width: auto;
            min-width: 0;
        }
        
        /* Critical: Allow nested question items to expand and push section content width */
        .section-content .option-container .question-item,
        .section-content .option-container .option-containers .option-container .question-item {
            width: auto;
            min-width: 250px;
        }
        
        /* Custom scrollbar for section content */
        .section-content::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .section-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .section-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .section-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .section-handle {
            cursor: grab;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .section-handle:active {
            cursor: grabbing;
        }
    </style>
@endpush
@section('content')
    <div class="px-sm-30 p-15 bd-b-one bd-c-stroke-2 d-flex justify-content-between align-items-center">
        <h4 class="fs-18 fw-700 lh-24 text-title-text">{{ $pageTitle ?? __('Questions Structure') }}</h4>
    </div>
    
    <script>
        // Make structure ID available to JavaScript
        window.structureId = {{ $structure->id ?? 'null' }};
        window.questions = {!! json_encode($questions ?? []) !!};
    </script>

    <div class="p-sm-30 p-15">
        <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ $pageTitle ?? __('Career Corner Form Structure') }}</h3>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="createSection">
                            <i class="fa-solid fa-folder-plus me-1"></i>{{ __('Create Section') }}
                        </button>
                        <button type="button" class="btn btn-primary" id="saveStructure">
                            <i class="fa-solid fa-save me-1"></i>{{ __('Save Structure') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Left side: Form structure canvas -->
                        <div class="col-md-8">
                            <h5 class="mb-3">{{ __('Form Structure') }}</h5>
                            <div id="formCanvas" class="question-list">
                                <!-- Items will be added here by JS -->
                            </div>
                        </div>

                        <!-- Right side: Available questions -->
                        <div class="col-md-4">
                            <h5 class="mb-3">{{ __('Available Questions') }}</h5>
                            <div id="availableQuestions" class="question-list">
                                @foreach($questions as $question)
                                    <div class="question-item" data-id="{{ $question->id }}">
                                        <span class="handle">⋮</span>
                                        {{ $question->question }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for question item -->
    <template id="questionItemTemplate">
        <div class="question-item d-flex align-items-start" data-id="">
            <span class="handle me-2">
                <i class="fa-solid fa-grip-lines"></i>
            </span>
            <div class="flex-grow-1">
                <div class="question-text"></div>
                <div class="text-muted small question-type"></div>
                <div class="option-containers">
                    <!-- Option containers added here for radio questions -->
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-item ms-2">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </template>

    <!-- Template for option container -->
    <template id="optionContainerTemplate">
        <div class="option-container" data-option="">
            <div class="title">
                <i class="fa-solid fa-level-down-alt me-1"></i>
                <span>If answer is: </span>
                <strong class="option-value"></strong>
            </div>
            <div class="option-list">
                <!-- Nested items dropped here -->
            </div>
        </div>
    </template>

    <!-- Template for section -->
    <template id="sectionTemplate">
        <div class="form-section" data-section-id="">
            <div class="section-header">
                <div class="section-title">
                    <span class="section-handle me-2">
                        <i class="fa-solid fa-grip-lines"></i>
                    </span>
                    <i class="fa-solid fa-folder"></i>
                    <input type="text" class="section-name-input" placeholder="Section Name (e.g., Educational Details)" value="">
                </div>
                <div class="section-actions">
                    <button type="button" class="btn btn-sm btn-light section-toggle" title="Collapse/Expand">
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light section-remove" title="Remove Section">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="section-content">
                <!-- Questions will be dropped here -->
            </div>
        </div>
    </template>
@endsection

@push('script')
    <!-- SortableJS for drag-and-drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- Form structure builder -->
    <script src="{{ asset('admin/custom/js/form-structure.js') }}"></script>
    
    <script>
        // Initialize with structure ID from Career Corner
        window.structureId = {{ $structure->id ?? 'null' }};
    </script>
@endpush