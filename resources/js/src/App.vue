<template>
    <PrimaryHeader />
    <h1>Hi, {{ username }}</h1>
    <main class="main">
        <router-view />
    </main>
</template>

<script>
import { defineComponent } from 'vue';
import PrimaryHeader from './components/PrimaryHeader.vue';

export default defineComponent ({
    components: { PrimaryHeader },
    setup () {
        return {
            username: 'Дмитрий'
        };
    },
});

</script>

<style scoped>

</style>