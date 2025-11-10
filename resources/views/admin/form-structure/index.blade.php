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
        
        /* Preview Modal Styles */
        #previewFormContainer {
            min-height: 400px;
        }
        
        .preview-section {
            margin-bottom: 2.5rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .preview-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #14b8a6;
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .preview-section-description {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 1rem;
            line-height: 1.6;
            padding: 0.75rem;
            background: #ffffff;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        
        .preview-question {
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        .preview-question:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #d1d5db;
        }
        
        .preview-question[data-depth="1"],
        .preview-question[data-depth="2"],
        .preview-question[data-depth="3"] {
            margin-left: 0;
        }
        
        .preview-question-label {
            display: block;
            font-weight: 600;
            font-size: 1.05rem;
            color: #1f2937;
            margin-bottom: 0.875rem;
            line-height: 1.5;
        }
        
        .preview-question-label .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }
        
        .preview-question-help {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
            font-style: italic;
            padding: 0.5rem;
            background: #f9fafb;
            border-radius: 0.375rem;
        }
        
        .preview-nested-questions {
            margin-top: 1.5rem;
            margin-left: 0;
            padding: 0;
            display: none !important;
        }
        
        .preview-nested-questions.show {
            display: block !important;
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .preview-radio-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        
        .preview-radio-option {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #ffffff;
        }
        
        .preview-radio-option:hover {
            background: #f8fafc;
            border-color: #14b8a6;
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(20, 184, 166, 0.1);
        }
        
        .preview-radio-option input[type="radio"] {
            margin-right: 0.875rem;
            cursor: pointer;
            width: 18px;
            height: 18px;
            accent-color: #14b8a6;
        }
        
        .preview-radio-option input[type="radio"]:checked + label {
            color: #14b8a6;
            font-weight: 600;
        }
        
        .preview-radio-option:has(input[type="radio"]:checked) {
            background: #f0fdfa;
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
        }
        
        .preview-checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        
        .preview-checkbox-option {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #ffffff;
        }
        
        .preview-checkbox-option:hover {
            background: #f8fafc;
            border-color: #14b8a6;
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(20, 184, 166, 0.1);
        }
        
        .preview-checkbox-option input[type="checkbox"] {
            margin-right: 0.875rem;
            cursor: pointer;
            width: 18px;
            height: 18px;
            accent-color: #14b8a6;
        }
        
        .preview-checkbox-option input[type="checkbox"]:checked + label {
            color: #14b8a6;
            font-weight: 600;
        }
        
        .preview-checkbox-option:has(input[type="checkbox"]:checked) {
            background: #f0fdfa;
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
        }
        
        .preview-text-input,
        .preview-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        
        .preview-textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .preview-text-input:focus,
        .preview-textarea:focus {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
        }
        
        /* Enhanced Select Dropdown Styling */
        .preview-select-input {
            width: 100%;
            padding: 0.875rem 2.5rem 0.875rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #1f2937;
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%23374151' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.875rem center;
            background-size: 1.125rem;
            border: 2px solid #d1d5db;
            border-radius: 0.625rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
            font-weight: 500;
        }
        
        .preview-select-input:hover {
            border-color: #14b8a6;
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.15);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%2314b8a6' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
            transform: translateY(-1px);
        }
        
        .preview-select-input:focus {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.2), 0 4px 16px rgba(0, 0, 0, 0.12);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%2314b8a6' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
        }
        
        .preview-select-input:active {
            transform: translateY(0);
        }
        
        /* Style the dropdown options */
        .preview-select-input option {
            padding: 0.875rem 1rem;
            color: #1f2937;
            background-color: #ffffff;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.6;
            border: none;
            margin: 0.25rem 0;
        }
        
        .preview-select-input option:first-child {
            color: #9ca3af;
            font-style: italic;
            font-weight: 400;
        }
        
        .preview-select-input option:checked,
        .preview-select-input option[selected] {
            background: linear-gradient(135deg, #f0fdfa 0%, #e6fffa 100%);
            color: #0d9488;
            font-weight: 600;
        }
        
        /* For Firefox */
        @-moz-document url-prefix() {
            .preview-select-input option {
                padding: 0.75rem 1rem;
            }
        }
        
        /* For Webkit browsers (Chrome, Safari, Edge) */
        @supports (-webkit-appearance: none) {
            .preview-select-input {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%23374151' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
            }
        }
        
        /* Disabled state */
        .preview-select-input:disabled {
            background-color: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            border-color: #e5e7eb;
            opacity: 0.6;
            box-shadow: none;
        }
        
        .preview-select-input:disabled:hover {
            transform: none;
            border-color: #e5e7eb;
            box-shadow: none;
        }
        
        /* Custom scrollbar for select dropdown (Webkit browsers) */
        .preview-select-input::-webkit-scrollbar {
            width: 8px;
        }
        
        .preview-select-input::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .preview-select-input::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .preview-select-input::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Modal Backdrop Blur Effect - More Specific Selectors */
        #previewModal + .modal-backdrop,
        .modal-backdrop.show,
        .modal-backdrop.fade.show {
            background-color: rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            opacity: 1 !important;
        }
        
        /* Blur the entire page when modal is open */
        body.modal-open {
            overflow: hidden;
        }
        
        /* Blur sidebar specifically - exclude modal (including zSidebar) */
        body.modal-open .zSidebar,
        body.modal-open .zMain-wrap .sidebar,
        body.modal-open .zMain-wrap aside,
        body.modal-open .sidebar:not(#previewModal):not(#previewModal *),
        body.modal-open aside:not(#previewModal):not(#previewModal *) {
            filter: blur(5px) !important;
            transition: filter 0.4s ease !important;
            pointer-events: none !important;
        }
        
        /* Blur main content area - but exclude modal */
        body.modal-open .zMainContent > *:not(#previewModal):not(.modal) {
            filter: blur(5px) !important;
            transition: filter 0.4s ease !important;
            pointer-events: none !important;
        }
        
        /* Blur header/navigation */
        body.modal-open .zMainContent > nav,
        body.modal-open .zMainContent > .navbar,
        body.modal-open .zMainContent > header {
            filter: blur(5px) !important;
            transition: filter 0.4s ease !important;
            pointer-events: none !important;
        }
        
        /* DON'T blur the entire wrapper - it will blur the modal inside */
        /* Instead, blur only specific children */
        
        /* CRITICAL: Ensure modal and ALL its children are NOT blurred - highest specificity */
        body.modal-open #previewModal,
        body.modal-open #previewModal *,
        body.modal-open #previewModal .modal-dialog,
        body.modal-open #previewModal .modal-content,
        body.modal-open #previewModal .modal-header,
        body.modal-open #previewModal .modal-body,
        body.modal-open #previewModal .modal-footer,
        body.modal-open .modal:not(.modal-backdrop),
        body.modal-open .modal:not(.modal-backdrop) *,
        body.modal-open .modal-dialog,
        body.modal-open .modal-content,
        body.modal-open .modal-header,
        body.modal-open .modal-body,
        body.modal-open .modal-footer {
            filter: none !important;
            -webkit-filter: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            pointer-events: auto !important;
        }
        
        /* Ensure modal backdrop is clickable and not blurred */
        body.modal-open .modal-backdrop,
        body.modal-open .modal-backdrop.show {
            filter: none !important;
            -webkit-filter: none !important;
            pointer-events: auto !important;
        }
        
        .preview-empty-state {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }
        
        .preview-empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
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
                        <button type="button" class="btn btn-outline-info" id="previewForm">
                            <i class="fa-solid fa-eye me-1"></i>{{ __('Preview Form') }}
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="createSection">
                            <i class="fa-solid fa-folder-plus me-1"></i>{{ __('Create Section') }}
                        </button>
                        <button type="button" class="btn btn-primary" id="saveStructure">
                            <i class="fa-solid fa-save me-1"></i>{{ __('Save Structure') }}
                        </button>
                        <button type="button" class="btn {{ $structure->is_published ? 'btn-success' : 'btn-outline-success' }}" id="togglePublish">
                            <i class="fa-solid {{ $structure->is_published ? 'fa-toggle-on' : 'fa-toggle-off' }} me-1"></i>
                            <span id="publishButtonText">{{ $structure->is_published ? __('Unpublish Form') : __('Publish Form') }}</span>
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

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">
                        <i class="fa-solid fa-eye me-2"></i>{{ __('Form Preview') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="previewFormContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">{{ __('Loading preview...') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- SortableJS for drag-and-drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <!-- Form structure builder -->
    <script src="{{ asset('admin/custom/js/form-structure.js') }}?v={{ time() }}"></script>
    
    <script>
        // Initialize with structure ID from Career Corner
        window.structureId = {{ $structure->id ?? 'null' }};
        
        // Attach preview button handler when document is ready
        $(document).ready(function() {
            // Use delegated event handler for reliability
            $(document).on('click', '#previewForm', function(e) {
                e.preventDefault();
                if (typeof window.showPreview === 'function') {
                    window.showPreview();
                }
            });
            
            // Handle publish/unpublish button
            $(document).on('click', '#togglePublish', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const $icon = $btn.find('i');
                const $text = $btn.find('#publishButtonText');
                const structureId = {{ $structure->id ?? 'null' }};
                
                if (!structureId) {
                    toastr.error('Structure ID not found');
                    return;
                }
                
                // Disable button during request
                $btn.prop('disabled', true);
                const originalIcon = $icon.attr('class');
                $icon.removeClass().addClass('fa-solid fa-spinner fa-spin me-1');
                
                $.ajax({
                    url: `{{ route('admin.form-structure.toggle-publish', ['id' => $structure->id ?? 0]) }}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .done(function(response) {
                    if (response.status) {
                        // Update button appearance
                        if (response.data.is_published) {
                            $btn.removeClass('btn-outline-success').addClass('btn-success');
                            $icon.removeClass().addClass('fa-solid fa-toggle-on me-1');
                            $text.text('{{ __('Unpublish Form') }}');
                        } else {
                            $btn.removeClass('btn-success').addClass('btn-outline-success');
                            $icon.removeClass().addClass('fa-solid fa-toggle-off me-1');
                            $text.text('{{ __('Publish Form') }}');
                        }
                        
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message || 'Error updating publish status');
                    }
                })
                .fail(function(xhr) {
                    const message = xhr.responseJSON?.message || 'Error updating publish status';
                    toastr.error(message);
                })
                .always(function() {
                    $btn.prop('disabled', false);
                });
            });
            
            // Remove blur effects when modal is closed
            $('#previewModal').on('hidden.bs.modal', function() {
                if (typeof window.removeModalBlur === 'function') {
                    window.removeModalBlur();
                } else if (typeof removeModalBlur === 'function') {
                    removeModalBlur();
                }
            });
            
            // Also handle Bootstrap 5 events
            $('#previewModal').on('hide.bs.modal', function() {
                if (typeof window.removeModalBlur === 'function') {
                    window.removeModalBlur();
                } else if (typeof removeModalBlur === 'function') {
                    removeModalBlur();
                }
            });
            
            // Handle when modal backdrop is clicked
            $(document).on('click', '.modal-backdrop', function() {
                setTimeout(function() {
                    if (typeof window.removeModalBlur === 'function') {
                        window.removeModalBlur();
                    }
                }, 300);
            });
        });
        
        // Use global function if available, otherwise define locally
        if (typeof window.removeModalBlur !== 'function') {
            window.removeModalBlur = function() {
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
                
                // Remove blur from any other elements
                $('body > *:not(.modal):not(.modal-backdrop)').css({
                    'filter': '',
                    'pointer-events': '',
                    'transition': ''
                });
                
                // Remove inline styles
                $('.zSidebar, .zMain-wrap, .sidebar, aside, .zMainContent, nav, .navbar, header').each(function() {
                    this.style.removeProperty('filter');
                    this.style.removeProperty('-webkit-filter');
                });
            };
        }
    </script>
@endpush