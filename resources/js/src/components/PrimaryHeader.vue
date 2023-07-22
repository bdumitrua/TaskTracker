<template>
   <header>
        <ul>
            <li><router-link to="/">Главная</router-link></li>
            <li><router-link to="/login">Войти</router-link></li>
            <li><router-link to="/register">Зарегистрироваться</router-link></li>
        </ul>
    </header>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    setup () {
        

        return {}
    }
})
</script>

<style scoped>

</style>