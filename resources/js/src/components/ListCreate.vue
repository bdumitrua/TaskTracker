<template>
    <div>
        <h4 class="text-center my-3">Create a new list</h4>
        <form @submit.prevent="createList">
            <div class="input-group mb-3">
                <input
                    type="text"
                    class="form-control"
                    placeholder="List name"
                    v-model="newListName"
                />
                <button
                    class="btn btn-outline-secondary"
                    type="submit"
                    id="button-addon2"
                >
                    Create List
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            newListName: "",
        };
    },
    methods: {
        async createList() {
            try {
                await this.$axios.post("/lists", { name: this.newListName });
                this.newListName = "";

                this.fetchMyLists();
            } catch (error) {
                console.error(error);
            }
        },
        async fetchMyLists() {
            this.$store.dispatch("fetchMyLists");
        },
    },
};
</script>

<style lang="scss" scoped></style>
