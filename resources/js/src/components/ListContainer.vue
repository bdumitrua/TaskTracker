<template>
    <div>
        <h2 class="text-center mt-5 mb-3">{{ title }}</h2>
        <div v-if="loading" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div v-else>
            <ListCard
                v-for="(list, index) in lists"
                :key="index"
                :list="list"
            />
            <div v-if="!lists.length" class="alert alert-info">
                No lists found.
            </div>
        </div>
    </div>
</template>

<script>
import ListCard from "@/components/ListCard.vue";

export default {
    components: { ListCard },
    props: {
        title: {
            type: String,
            required: true,
        },
        lists: {
            type: Array,
            required: true,
        },
        loading: {
            type: Boolean,
            required: true,
        },
    },
};
</script>
