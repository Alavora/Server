const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.copy('resources/assets/images', 'public/images', false);
// mix.copy('resources/css/app.css', 'public/css/app.css', false);
mix.copy('resources/css/welcome.css', 'public/css/welcome.css', false);
mix.copy('resources/js/welcome.js', 'public/js/welcome.js', false);
