import { createApp } from "vue";
import App from "./src/App.vue";
import axiosInstance from "./src/axios/instance.js";
import Router from "./src/router/router.js";
import Store from "./src/store/store.js";

const app = createApp(App);

app.config.globalProperties.$axios = axiosInstance;

app.use(Store).use(Router).mount("#app");
