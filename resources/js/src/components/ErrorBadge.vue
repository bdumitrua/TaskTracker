<template>
    <div
        v-if="errors"
        v-for="(error, index) in errors"
        :key="index"
        class="alert alert-danger my-2"
        role="alert"
    >
        {{ error[0] }}
    </div>
    <div v-if="error" class="alert alert-danger my-2" role="alert">
        {{ error }}
    </div>
</template>

<script>
export default {
    props: {
        errors: {
            type: Object,
        },
        error: {
            type: String,
        },
    },
};
</script>

<style lang="scss" scoped></style>
