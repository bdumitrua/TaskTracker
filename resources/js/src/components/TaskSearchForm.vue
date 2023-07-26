<template>
    <form @submit.prevent="onSearch" class="mb-3">
        <div class="mb-3">
            <label for="tags" class="form-label">Search by Tags</label>
            <input
                type="text"
                class="form-control"
                id="tags"
                placeholder="Enter tags (comma separated)"
                v-model="tagInput"
            />
        </div>
        <div class="d-flex justify-content-center mt-3">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            tagInput: "",
        };
    },
    props: {
        searchTasks: Function,
    },
    methods: {
        onSearch() {
            if (this.tagInput == "") {
                return this.fetchTasks();
            }

            const tags = this.tagInput.split(",").map((tag) => tag.trim());
            this.searchTasks(tags);
        },
        async fetchTasks() {
            this.$emit("fetchTasks");
        },
    },
};
</script>
