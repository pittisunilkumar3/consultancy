@extends('layouts.app')

@push('title')
    {{ $pageTitle ?? __('Questions Structure') }}
@endpush

@push('style')
    <style>
        /* Canvas with grid background - more visible */
        #formCanvas {
            background-image: 
                linear-gradient(rgba(0, 0, 0, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
            background-size: 25px 25px;
            background-position: 0 0, 0 0;
            background-color: #fafafa;
        }
        
        /* Empty canvas state */
        #formCanvas:empty::before {
            content: 'Drag questions here to build your form structure';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #94a3b8;
            font-size: 1rem;
            font-weight: 500;
            pointer-events: none;
            text-align: center;
            white-space: nowrap;
        }
        
        #formCanvas:not(:empty)::before {
            display: none;
        }
        
        /* Right side - Available questions with vertical scroll */
        #availableQuestions {
            min-height: 400px;
            max-height: 600px;
            overflow-y: auto;
            overflow-x: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1rem;
            background: #fafafa;
            transition: border-color 0.3s ease;
        }
        
        #availableQuestions:hover {
            border-color: #cbd5e1;
        }
        
        /* Left side - Form canvas with horizontal scroll (primary) and optional vertical scroll */
        #formCanvas {
            min-height: 400px;
            max-height: 600px;
            overflow-x: auto;
            overflow-y: auto;
            border: 2px dashed #cbd5e1;
            border-radius: 0.375rem;
            padding: 1rem;
            position: relative;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        }
        
        #formCanvas:not(:empty) {
            border-style: solid;
            border-width: 1px;
            background-color: #ffffff;
        }
        
        #formCanvas:focus-within {
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            position: relative;
            animation: fadeInSlide 0.4s ease-out;
        }
        
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .question-item:hover {
            border-color: #14b8a6;
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.15);
            transform: translateY(-2px);
        }
        
        .question-item:active {
            transform: scale(0.98);
        }
        .option-container {
            margin-left: 2rem;
            margin-top: 0.5rem;
            padding: 0.75rem;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        /* Visual depth indicator for nested levels - default removed, use data-level */
        .option-container::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #14b8a6, #0d9488);
            border-radius: 2px;
            opacity: 0.8;
        }
        
        .option-container:hover {
            background: #f1f5f9;
            border-color: #14b8a6;
            border-style: solid;
            box-shadow: 0 2px 8px rgba(20, 184, 166, 0.1);
        }
        
        .option-container .title {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .option-container .title i {
            color: #14b8a6;
        }
        .option-list {
            min-height: 2rem;
        }
        .sortable-ghost {
            opacity: 0.4;
            background: #e2e8f0;
            border: 2px dashed #14b8a6;
            transform: rotate(2deg);
        }
        
        /* Drop zone highlight */
        .sortable-drag-over {
            background: rgba(20, 184, 166, 0.1) !important;
            border: 2px dashed #14b8a6 !important;
            border-radius: 0.5rem;
        }
        
        /* Drop indicator line */
        .sortable-drag-over::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: #14b8a6;
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
        }
        .handle {
            cursor: grab;
            color: #94a3b8;
            padding: 0 0.5rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .handle:hover {
            color: #14b8a6;
            transform: scale(1.2);
        }
        
        .handle:active {
            cursor: grabbing;
            transform: scale(1.1);
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
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            color: white;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: move;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .section-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .section-header:hover::before {
            left: 100%;
        }
        
        .section-header:hover {
            background: linear-gradient(135deg, rgb(34, 154, 140) 0%, #087a70 100%);
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
            transform: translateY(-1px);
        }
        
        /* Section animation on create */
        .form-section {
            animation: sectionFadeIn 0.5s ease-out;
        }
        
        @keyframes sectionFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Section shake animation for errors */
        .form-section.shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        /* Section bounce on save */
        .form-section.saved {
            animation: bounce 0.6s ease;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
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
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, padding 0.4s ease-out;
        }
        
        .section-content:not(.collapsed) {
            animation: slideDown 0.4s ease-out;
        }
        
        @keyframes slideDown {
            from {
                max-height: 0;
                opacity: 0;
            }
            to {
                max-height: 2000px;
                opacity: 1;
            }
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
            transition: all 0.2s ease;
        }
        
        .section-handle:hover {
            color: white;
            transform: scale(1.2);
        }
        
        .section-handle:active {
            cursor: grabbing;
            transform: scale(1.1);
        }
        
        /* Section action buttons */
        .section-actions .btn {
            transition: all 0.2s ease;
        }
        
        .section-actions .btn:hover {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.3) !important;
        }
        
        .section-actions .btn:active {
            transform: scale(0.95);
        }
        
        /* Question count badge in section */
        .section-question-count {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            padding: 0.25rem 0.625rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        
        /* Remove item button */
        .remove-item {
            transition: all 0.2s ease;
        }
        
        .remove-item:hover {
            transform: scale(1.1);
            background: #ef4444 !important;
            border-color: #ef4444 !important;
            color: white !important;
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        /* Loading overlay */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 0.375rem;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e5e7eb;
            border-top-color: #14b8a6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Success flash animation */
        .success-flash {
            animation: successFlash 0.6s ease-out;
        }
        
        @keyframes successFlash {
            0% { background-color: transparent; }
            50% { background-color: rgba(20, 184, 166, 0.2); }
            100% { background-color: transparent; }
        }
        
        /* Ripple effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        
        .ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .ripple:active::after {
            width: 300px;
            height: 300px;
        }
        
        /* Typography improvements */
        .question-text {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        
        .question-type {
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Icon animations */
        .section-toggle i {
            transition: transform 0.3s ease;
        }
        
        .section-content.collapsed ~ .section-header .section-toggle i {
            transform: rotate(-90deg);
        }
        
        /* Tooltip styles */
        [title] {
            position: relative;
        }
        
        /* Improved spacing */
        .option-container {
            margin-left: 2.5rem;
        }
        
        .option-container .option-container {
            margin-left: 2rem;
        }
        
        /* Depth level colors for nested items - must override default */
        .option-container[data-level="0"]::before,
        .option-container:not([data-level])::before {
            background: linear-gradient(to bottom, #14b8a6, #0d9488) !important;
        }
        
        .option-container[data-level="1"]::before {
            background: linear-gradient(to bottom, #06b6d4, #0891b2) !important;
        }
        
        .option-container[data-level="2"]::before {
            background: linear-gradient(to bottom, #8b5cf6, #7c3aed) !important;
        }
        
        .option-container[data-level="3"]::before {
            background: linear-gradient(to bottom, #f59e0b, #d97706) !important;
        }
        
        .option-container[data-level="4"]::before {
            background: linear-gradient(to bottom, #ef4444, #dc2626) !important;
        }
        
        .option-container[data-level="5"]::before {
            background: linear-gradient(to bottom, #ec4899, #db2777) !important;
        }
        
        /* Button improvements */
        #createSection, #saveStructure {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        #createSection:hover, #saveStructure:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        #createSection:active, #saveStructure:active {
            transform: translateY(0);
        }
        
        /* Progress bar for save */
        .save-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: #14b8a6;
            width: 0%;
            transition: width 0.3s ease;
            border-radius: 0 0 0.375rem 0.375rem;
        }
        
        /* Dragging state */
        .dragging {
            opacity: 0.5;
            transform: scale(0.95);
        }
        
        .drag-active {
            border-color: #14b8a6;
        }
        
        /* Ripple effect for buttons */
        #createSection, #saveStructure, .section-actions .btn, .remove-item {
            position: relative;
            overflow: hidden;
        }
        
        #createSection::after, #saveStructure::after, .section-actions .btn::after, .remove-item::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        #createSection:active::after, #saveStructure:active::after, .section-actions .btn:active::after, .remove-item:active::after {
            width: 300px;
            height: 300px;
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