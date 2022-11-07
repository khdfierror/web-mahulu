import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

import Components from "unplugin-vue-components/vite";
import Icons from "unplugin-icons/vite";
import IconsResolver from "unplugin-icons/resolver";
import AutoImport from "unplugin-auto-import/vite";

const { resolve } = require("path");

export default ({ mode, ssrBuild }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };

    return defineConfig({
        plugins: [
            laravel({
                input: "resources/js/app.js",
                ssr: "resources/js/ssr.js",
                refresh: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            Components({
                dirs: [
                    "./resources/js/Shared",
                ],
                resolvers: [
                    IconsResolver({
                        componentPrefix: "icon", // <--
                    }),
                    (componentName) => {
                        if ((['Head', 'Link']).indexOf(componentName) > -1)
                            return { name: componentName, from: '@inertiajs/inertia-vue3' }
                      },
                ],
            }),
            Icons({
                autoInstall: true,
            }),
            AutoImport({
                imports: [
                    'vue',
                    '@vueuse/core',
                    {
                        '@inertiajs/inertia-vue3': [
                            'useForm', 'useRemember', 'usePage'
                        ]
                    },
                ],
                vueTemplate: true,
                dirs: [
                "./resources/js/Composables",
                ...(ssrBuild ? ["./resources/js/Ssr"] : []),
                ],
            }),
        ],
        ssr: {
            noExternal: ['@inertiajs/server'],
        },
        resolve: {
            alias: [
                {
                    find: "@images",
                    replacement: resolve("./resources/images"),
                },
                {
                    find: "@",
                    replacement: resolve("./resources/js"),
                },
                {
                    find: "ziggy",
                    replacement: resolve('./vendor/tightenco/ziggy/dist')
                }
            ],
        },
    });
}
