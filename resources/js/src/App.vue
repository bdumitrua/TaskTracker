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
        await this.$store.dispatch("getUser");
        this.$store.dispatch("fetchAllLists");

        setInterval(() => {
            this.$store.dispatch("fetchAllLists");
        }, 15000);
    },
});
</script>

<style>
label,
button,
a {
    cursor: pointer;
}
/* bootstrap moment */
p {
    margin-bottom: 0;
}
</style>
