const mix = require('laravel-mix');

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

mix.copyDirectory('resources/assets/libs/fontawesome/webfonts', 'public/assets/web/webfonts/');
mix.copyDirectory('resources/assets/libs/fontawesome/webfonts', 'public/assets/admin/webfonts/');

//Admin MIX
mix.styles([
    'resources/assets/libs/adminlte/dist/css/adminlte.css',
    'resources/assets/libs/fontawesome/css/all.css',
    'resources/assets/libs/alertifyjs/css/alertify.css',
    'resources/assets/libs/alertifyjs/css/themes/bootstrap.css'
    
], 'public/assets/admin/css/bundle.css');

mix.styles([
    'resources/assets/css/adminApp.css'
], 'public/assets/admin/css/App.css');


mix.scripts([
    'resources/assets/libs/jquery-3.5.1.min.js',
    'resources/assets/libs/bootstrap/js/bootstrap.bundle.js',
    'resources/assets/libs/alertifyjs/alertify.js',
    'resources/assets/libs/adminlte/dist/js/adminlte.js',
    
], 'public/assets/admin/js/bundle.js');


mix.scripts([
    'resources/assets/js/adminApp.js',
    
], 'public/assets/admin/js/App.js');



//Frontend Mix
mix.styles([
    'resources/assets/libs/alertifyjs/css/alertify.css',
    'resources/assets/libs/alertifyjs/css/themes/bootstrap.css',
    'resources/assets/libs/bootstrap/css/bootstrap.css',
    'resources/assets/libs/fontawesome/css/all.css',
    'resources/assets/css/css-load.css'
], 'public/assets/web/css/bundle.css');

mix.scripts([
    'resources/assets/libs/jquery-3.5.1.min.js',
    'resources/assets/libs/bootstrap/js/bootstrap.bundle.js',
    'resources/assets/libs/alertifyjs/alertify.js',
  'resources/assets/libs/lazysizes.min.js',
    
], 'public/assets/web/js/bundle.js');


mix.styles([
    'resources/assets/css/webApp.css'
], 'public/assets/web/css/App.css');


mix.scripts([
    'resources/assets/js/webApp.js',
], 'public/assets/web/js/App.js');



