import { createApp } from "vue";
import App from "./src/App.vue";
import "./src/axios.js";
import Router from "./src/router/router.js";
import Store from "./src/store/store.js";

createApp(App).use(Store).use(Router).mount("#app");
