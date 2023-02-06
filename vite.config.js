import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import StylelintPlugin from 'vite-plugin-stylelint';
import purge from '@erbelion/vite-plugin-laravel-purgecss';
import inject from '@rollup/plugin-inject';



export default defineConfig({
    server: {
        hmr: {
            host: 'localhost'
        }
    },
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"], // add scss file
            refresh: true,
        }),
        purge({
            templates: ['blade']
        }),
        StylelintPlugin({
            fix: true,
            quite: true,
            files: ['resources/sass/**/*.scss']
        }),
        inject({
            $: 'jquery',
            jQuery: 'jquery',
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': 'node_modules/bootstrap',
            '~splide': 'node_modules/@splidejs/splide',
        }
    },
});
