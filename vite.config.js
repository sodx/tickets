import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import htmlPurge from 'vite-plugin-purgecss'
import StylelintPlugin from 'vite-plugin-stylelint';

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"], // add scss file
            refresh: true,
        }),
        htmlPurge({ fontFace: true }),
        StylelintPlugin({
            fix: true,
            //quite: true,
            files: ['resources/sass/**/*.scss']
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': 'node_modules/bootstrap',
        }
    },
});
