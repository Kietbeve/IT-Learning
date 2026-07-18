import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'Modules/Exam/resources/assets/js/exam-security.js', ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    // Không dùng `noDiscovery`.
    // Vì Vite sẽ chỉ optimize các package trong `include`,
    // khiến Quill/PowerGrid không được xử lý đúng và gây lỗi JS.
    // optimizeDeps: {
    //     noDiscovery: true,
    //     include: ['flatpickr', 'flatpickr/dist/l10n/'],
    // },
});
