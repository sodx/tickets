import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import StylelintPlugin from 'vite-plugin-stylelint';
import purge from '@erbelion/vite-plugin-laravel-purgecss'


export default defineConfig({
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
    ],
    resolve: {
        alias: {
            '~bootstrap': 'node_modules/bootstrap',
        }
    },
});
