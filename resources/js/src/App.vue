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
        ...mapGetters(["user", "myLists", "editLists", "viewLists"]),
    },
    async created() {
        this.$store.dispatch("fetchMyLists");
        this.$store.dispatch("fetchEditLists");
        this.$store.dispatch("fetchViewLists");
        this.$store.dispatch("fetchUser");
    },
});
</script>

<style scoped></style>
