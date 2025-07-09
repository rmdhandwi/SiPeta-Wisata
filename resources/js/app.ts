import 'primeicons/primeicons.css';
import '../css/app.css';

import 'vue-toast-notification/dist/theme-bootstrap.css';
// import 'vue-toast-notification/dist/theme-default.css';
// import 'vue-toast-notification/dist/theme-sugar.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

import { definePreset } from '@primeuix/themes';
import Aura from '@primeuix/themes/aura';
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';

import ToastPlugin from 'vue-toast-notification';


// Custom theme preset
const MyPreset = definePreset(Aura, {
    semantic: {
        // primary: {
        //     50:  '#e6f2fb',
        //     100: '#cce5f7',
        //     200: '#99ccf0',
        //     300: '#66b2e8',
        //     400: '#3399e1',
        //     500: '#1e7fce', // warna utama
        //     600: '#196fb5',
        //     700: '#155f9b',
        //     800: '#104f82',
        //     900: '#0c3f68',
        //     950: '#062842',
        // },
        primary: {
            50:  '#f5f5f5',
            100: '#e0e0e0',
            200: '#bfbfbf',
            300: '#999999',
            400: '#4f4f4f',
            500: '#2e2e2e', // warna utama tombol
            600: '#242424',
            700: '#1a1a1a',
            800: '#121212',
            900: '#0a0a0a',
            950: '#000000',
        }
       
    },
});

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: MyPreset,
                    options: {
                        darkModeSelector: false,
                    },
                },
            })
            .use(ConfirmationService)
            .use(ToastPlugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
        // color: '#34d399',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
