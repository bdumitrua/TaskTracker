<template>
    <div
        class="d-flex flex-column align-items-center gap-5 my-5 justify-content-center"
    >
        <div class="mx-4">Count: {{ counter }}</div>
        <button class="btn btn-primary mx-5" @click="increment">
            Increment
        </button>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent } from "vue";
import { useStore } from "vuex";

export default defineComponent({
    setup() {
        const store = useStore();
        const counter = computed(() => {
            return store.state.count;
        });

        const increment = () => {
            store.commit("increment");
        };

        return { counter, increment };
    },
});
</script>

<style scoped></style>
