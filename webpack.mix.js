const mix = require('laravel-mix');
const dotEnv = require('dotenv');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

dotEnv.config({
    path: '.env'
});

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .alias({
        ziggy: path.resolve('vendor/tightenco/ziggy/dist')
    })
    .copyDirectory('resources/images', 'public/images')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
        require('autoprefixer')
    ]);

