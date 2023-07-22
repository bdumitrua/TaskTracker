<template>
    <div>
        Домашняя
        Count: {{ counter }}
    </div>
    <button @click="increment">Increment</button>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue';
import { useStore } from 'vuex';

export default defineComponent({
    setup () {
        const store = useStore()
        const counter = computed(() => {
            return store.state.count
        })

        const increment = () => {
            store.commit('increment')
        }

        return {counter, increment}
    }
})
</script>

<style scoped>

</style>