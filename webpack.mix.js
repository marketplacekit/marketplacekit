let mix = require('laravel-mix');

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
/*
mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
*/
mix.sass('resources/themes/default/sass/app.scss', 'public/themes/default/css');
//mix.sass('resources/themes/boomerang/sass/app.scss', 'public/themes/boomerang/css');
//mix.sass('resources/themes/novum/sass/app.scss', 'public/themes/novum/css');
//mix.sass('resources/themes/tabler/sass/app.scss', 'public/themes/tabler/css');
//mix.sass('resources/themes/urban/sass/app.scss', 'public/themes/urban/css');
//mix.sass('resources/themes/novum-rtl/sass/app.scss', 'public/themes/novum-rtl/css');
