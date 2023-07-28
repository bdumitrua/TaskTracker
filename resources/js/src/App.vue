<template>
    <PrimaryHeader />
    <main class="main mx-5">
        <router-view />
    </main>
</template>

<script>
import { defineComponent } from "vue";
import { mapGetters } from "vuex";
import PrimaryHeader from "./components/PrimaryHeader.vue";

export default defineComponent({
    components: { PrimaryHeader },
    setup() {
        return {};
    },
    computed: {
        ...mapGetters(["user", "lists"]),
    },
    async created() {
        this.$store.dispatch("fetchLists");
        this.$store.dispatch("fetchUser");
    },
});
</script>

<style scoped></style>
