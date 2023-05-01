import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import StylelintPlugin from 'vite-plugin-stylelint';
import { ViteMinifyPlugin } from 'vite-plugin-minify'
//import purge from '@erbelion/vite-plugin-laravel-purgecss';
import inject from '@rollup/plugin-inject';



export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/sass/app-dark.scss", "resources/sass/app-dark-blue.scss", "resources/js/app.js"], // add scss file
            refresh: true,
        }),
        StylelintPlugin({
            fix: true,
            quite: true,
            files: ['resources/sass/**/*.scss']
        }),
        ViteMinifyPlugin({}),
    ],
    resolve: {
        alias: {
            '~bootstrap': 'node_modules/bootstrap',
            '~splide': 'node_modules/@splidejs/splide',
        }
    },
});
