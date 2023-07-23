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
            <span class="d-flex gap-5">
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
        </ul>
    </header>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    setup() {
        return {};
    },
});
</script>

<style scoped></style>
