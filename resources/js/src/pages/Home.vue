<template>
    <div
        class="d-flex flex-column align-items-center gap-5 my-5 justify-content-center"
    >
        <h2 v-if="user">Hi, {{ user.name }}</h2>
        <h2 v-else>You're not logged in</h2>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "Home",
    methods: {},
    computed: {
        ...mapGetters(["user"]),
    },
};
</script>

<style scoped></style>
