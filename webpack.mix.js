const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.alias({
    '@': path.resolve('resources/js'),
});

// Base app JS (existing behaviour)
mix.js('resources/js/app.js', 'public/js');

// RAG Training React/TypeScript entry
mix.ts('resources/js/rag-training.tsx', 'public/js').react();

// Tailwind CSS (for React components)
mix.postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Existing Sass pipeline
mix.sass('resources/sass/app.scss', 'public/css')
   .sourceMaps();
