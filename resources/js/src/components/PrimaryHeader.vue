<template>
    <header class="px-4 py-3 bg-dark text-white">
        <ul
            class="d-flex gap-5 list-unstyled justify-content-between align-items-center container m-auto"
        >
            <li>
                <router-link to="/">
                    <img
                        :src="logoPath"
                        style="height: 40px; width: 150px"
                        alt="TASKTRACKER"
                    />
                </router-link>
            </li>
            <span v-if="!user" class="d-flex gap-5">
                <li>
                    <router-link class="btn btn-outline-light" to="/login"
                        >Log in</router-link
                    >
                </li>
                <li>
                    <router-link class="btn btn-outline-light" to="/register"
                        >Register</router-link
                    >
                </li>
            </span>
            <span v-if="user" class="d-flex gap-5">
                <li>
                    <router-link class="btn btn-outline-light" to="/lists"
                        >My Lists</router-link
                    >
                </li>
                <li>
                    <a
                        class="btn btn-outline-light"
                        href="javascript:void(0)"
                        @click="handleLogout"
                        >Logout</a
                    >
                </li>
            </span>
        </ul>
    </header>
</template>

<script>
import logo from "@/images/logo.svg";
import { defineComponent } from "vue";
import { mapGetters } from "vuex";

export default defineComponent({
    setup() {
        return { logoPath: logo };
    },
    methods: {
        handleLogout() {
            localStorage.removeItem("access_token");
            this.$router.push("/");
            this.$store.dispatch("setUser", null);
        },
    },
    computed: {
        ...mapGetters(["user"]),
    },
});
</script>

<style scoped></style>
