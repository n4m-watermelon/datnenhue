const mix = require('laravel-mix');
let ImageminPlugin = require('imagemin-webpack-plugin').default;

mix.webpackConfig({
    resolve: {
        alias: {
            'jquery': path.join(__dirname, 'node_modules/jquery/src/jquery')
        }
    },
    plugins: [
        new ImageminPlugin({
            pngquant: {
                quality: '95-100',
            },
            test: /\.(jpe?g|png|gif|svg)$/i
        }),
    ],
});
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

// source and distribution folder
let source = 'resources/';
let dest = 'public/';
// Bootstrap scss source
let bootstrapSass = {
    in: 'node_modules/bootstrap-sass/'
};
// FontAwesome
let fontAwesome = {
    in: 'node_modules/font-awesome/'
};
// fonts
let fonts = {
    in: [
        source + 'fonts',
        bootstrapSass.in + 'fonts',
        fontAwesome.in + 'fonts'
    ],
    out: dest + 'fonts'
};
mix.setPublicPath('public').sass(source + 'sass/app.scss', dest + 'css/app.min.css', {implementation: require("node-sass")}).sass(source + 'sass/site.scss', dest + 'css/site.min.css', {implementation: require("node-sass")})
    .autoload({
        'jquery': ['jQuery', '$'],
    })
    .js([
        source + 'js/app.js',
    ], dest + 'js/app.js')
    .extract()
    .scripts([
        source + 'js/pages/*',
        source + 'js/components/*'
    ], dest + 'js/all.js')
    .options({uglify: {uglifyOptions: {output: {beautify: false, ascii_only: true}}}})
    .copy(source + 'libs', dest + 'libs', false)
    .copy(source + 'images', dest + 'images', false)
    .sourceMaps();


mix.browserSync({
    proxy: 'local.chothuenha360.com',
    port: 8000,
    files: [
        'app/**/*',
        'public/**/*',
        'resources/**/*',
        'resources/views/**/*',
        'resources/lang/**/*',
        'routes/**/*'
    ],
});
