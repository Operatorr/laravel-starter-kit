import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import livewire from '@defstudio/vite-livewire-plugin';
import prismjs from 'vite-plugin-prismjs';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        livewire(),
        prismjs({
            languages: ['markup', 'php', 'html', 'javascript'],   // load what you need
            plugins: ['line-numbers', 'copy-to-clipboard'],
            theme: 'okaidia',      // or any Prism theme
            css: true,
        }),
    ],
    server: {
        cors: true,
    },
});