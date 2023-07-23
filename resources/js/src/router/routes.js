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
        component: Tasks,
    },
];

export default routes;
