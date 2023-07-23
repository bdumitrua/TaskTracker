<template>
    <header class="px-4 py-3 bg-dark text-white">
        <ul
            class="d-flex gap-5 list-unstyled justify-content-between align-items-center container m-auto"
        >
            <li>
                <router-link class="btn btn-outline-light" to="/"
                    >Главная</router-link
                >
            </li>
            <span v-if="!user" class="d-flex gap-5">
                <li>
                    <router-link class="btn btn-outline-light" to="/login"
                        >Войти</router-link
                    >
                </li>
                <li>
                    <router-link class="btn btn-outline-light" to="/register"
                        >Зарегистрироваться</router-link
                    >
                </li>
            </span>
            <span v-if="user" class="d-flex gap-5">
                <li>
                    <a
                        class="btn btn-outline-light"
                        href="javascript:void(0)"
                        @click="handleLogout"
                        >Выйти</a
                    >
                </li>
            </span>
        </ul>
    </header>
</template>

<script>
import { defineComponent } from "vue";
import { mapGetters } from "vuex";

export default defineComponent({
    setup() {
        return {};
    },
    methods: {
        handleLogout() {
            localStorage.removeItem("access_token");
            this.$store.dispatch("user", null);
            this.$router.push("/");
        },
    },
    computed: {
        ...mapGetters(["user"]),
    },
});
</script>

<style scoped></style>
