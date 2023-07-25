import Error404 from "@/pages/404.vue";
import Home from "@/pages/Home.vue";
import Lists from "@/pages/Lists.vue";
import Login from "@/pages/Login.vue";
import Register from "@/pages/Register.vue";
import Tasks from "@/pages/Tasks.vue";

const routes = [
    {
        path: "/",
        component: Home,
    },
    {
        path: "/login",
        component: Login,
    },
    {
        path: "/register",
        component: Register,
    },
    {
        path: "/lists",
        component: Lists,
    },
    {
        path: "/lists/:id",
        name: "Tasks",
        component: Tasks,
    },
    {
        path: "/:catchAll(.*)",
        name: "Error404",
        component: Error404,
    },
];

export default routes;
