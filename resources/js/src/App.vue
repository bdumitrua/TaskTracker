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
        this.$store.dispatch("fetchAllLists");
        this.$store.dispatch("getUser");
    },
});
</script>

<style scoped></style>
