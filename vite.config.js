import { defineConfig } from 'vite';
import { checker } from 'vite-plugin-checker';

import laravel from 'laravel-vite-plugin';
// import react from '@vitejs/plugin-react';
// import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel([
            // 'resources/css/app.css',
            'resources/js/app.js',
            'resources/scss/app.scss',
            'resources/js/enable_searchable_select.ts',
            'resources/js/enable_tooltip.ts',
            'resources/js/enable_popover.ts',
            'resources/js/enable_inputmask.ts',
            'resources/js/enable_custom_inputmask.ts'
        ],
            {
                refresh: true,
            }),

        checker({
            typescript: true,
            overlay: true,
        }),
        // react(),
        // vue({
        //     template: {
        //         transformAssetUrls: {
        //             base: null,
        //             includeAbsolute: false,
        //         },
        //     },
        // }),
    ],
});