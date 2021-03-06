let mix = require("laravel-mix");

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

// setResourceRoot("../") is required to correctly resolve font paths,
// in case of application reachable by a domain subfolder
mix.setResourceRoot("../")
    .js("resources/assets/js/app.js", "public/js")
    .js("resources/assets/js/statistics.js", "public/js")
    .js("resources/assets/js/lists.js", "public/js")
    .sass("resources/assets/sass/app.scss", "public/css")
    .sass("resources/assets/sass/lists.scss", "public/css");
