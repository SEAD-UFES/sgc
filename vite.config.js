import { defineConfig } from 'vite';
import { checker } from 'vite-plugin-checker';

import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/css/app.css',
                'resources/js/app.js',
                'resources/scss/app.scss',
                'resources/js/enable_searchable_select.ts',
                'resources/js/enable_tooltip.ts',
                'resources/js/enable_popover.ts',
                'resources/js/enable_inputmask.ts',
                'resources/js/enable_custom_inputmask.ts'
            ],
            refresh: true,
        }),

        checker({
            typescript: true,
            overlay: true,
        }),
    ],
});
