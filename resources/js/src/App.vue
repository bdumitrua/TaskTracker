<template>
    <PrimaryHeader />
    <main class="main mx-5">
        <router-view />
    </main>
</template>

<script>
import { defineComponent } from "vue";
import PrimaryHeader from "./components/PrimaryHeader.vue";

export default defineComponent({
    components: { PrimaryHeader },
    setup() {
        return {};
    },
    async created() {
        try {
            const response = await this.$axios.get("/auth/user");

            this.$store.dispatch("user", response.data[0]);
        } catch (error) {
            console.log(error);
        }
    },
});
</script>

<style scoped></style>
