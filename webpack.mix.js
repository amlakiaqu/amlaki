const {mix} = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .babel(['resources/assets/js/login.js'], 'public/js/login.js')
    .babel(['resources/assets/js/resources/request.js'], 'public/js/resources/request.js')
    .babel(['resources/assets/js/resources/post.js'], 'public/js/resources/post.js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/index.scss', 'public/css');

if (mix.config.inProduction) {
    mix.version();
}

mix.copy('resources/assets/js/home.js', 'public/js')
    .copyDirectory('resources/assets/js/vendors/', 'public/js');
// .copy('node_modules/bootstrap-validator/dist/validator.min.js', 'public/js');

mix.copy('resources/assets/css/vendors/', 'public/css');

/* Copy Lang files */
mix.copyDirectory('resources/assets/js/lang/', 'public/js/lang');
