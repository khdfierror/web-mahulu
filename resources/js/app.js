import './bootstrap';
import '../css/app.css';
import 'v-calendar/dist/style.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy/vue.m';
import FloatingVue from 'floating-vue';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(FloatingVue, {
                themes: {
                    tooltip: {
                        delay: {
                            show: 100,
                        },
                        html: true,
                    },
                },
            })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
