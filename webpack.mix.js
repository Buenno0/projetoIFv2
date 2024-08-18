const mix = require('laravel-mix');

mix.css('resources/css/app.css', 'public/css')
   .js('resources/js/app.js', 'public/js');


// Adicione Bootstrap no seu arquivo Sass
mix.sass('resources/sass/app.scss', 'public/css').options({
    postCss: [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ],
});
