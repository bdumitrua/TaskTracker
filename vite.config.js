import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";
import { defineConfig } from "vite";
import EnvironmentPlugin from "vite-plugin-environment";

export default defineConfig({
    publicDir: "public",
    base: "/public/",
    server: {
        hmr: {
            host: "localhost",
        },
    },
    plugins: [
        vue(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        EnvironmentPlugin(["APP_URL"]),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./resources/js/src"),
        },
    },
});
