<template>
    <div class="container">
        <h2 class="text-center my-5">My Lists</h2>
        <div class="mb-5">
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
        <div v-if="loading" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div v-else>
            <div class="card mb-3" v-for="(list, index) in lists" :key="index">
                <div class="card-body">
                    <div
                        class="d-flex justify-content-between align-items-center"
                    >
                        <div>
                            <form @submit.prevent="saveList(list)">
                                <input
                                    type="text"
                                    v-if="list.isEditing"
                                    v-model="list.newName"
                                    class="form-control"
                                />
                                <h5 v-else class="card-title">
                                    <router-link :to="'lists/' + list.id">
                                        {{ list.name }}
                                    </router-link>
                                </h5>
                                <button
                                    @submit="saveList(list)"
                                    hidden
                                    type="submit"
                                ></button>
                            </form>
                        </div>
                        <div class="d-flex gap-3">
                            <button
                                v-if="!list.isEditing"
                                class="btn btn-primary btn-sm"
                                @click="startEdit(list)"
                            >
                                Edit
                            </button>
                            <button
                                v-else
                                class="btn btn-success btn-sm"
                                @click="saveList(list)"
                            >
                                Save
                            </button>
                            <button
                                class="btn btn-danger btn-sm"
                                @click="deleteList(list)"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="!lists.length" class="alert alert-info">
                No lists found.
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            lists: [],
            loading: false,
            newListName: "",
        };
    },
    async created() {
        await this.loadLists();
    },
    methods: {
        async loadLists() {
            this.loading = true;
            try {
                const response = await this.$axios.get("/lists");
                this.lists = response.data.map((list) => ({
                    ...list,
                    isEditing: false,
                    newName: list.name,
                }));
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
        async createList() {
            try {
                await this.$axios.post("/lists", { name: this.newListName });
                this.newListName = "";
                await this.loadLists();
            } catch (error) {
                console.error(error);
            }
        },
        async deleteList(list) {
            try {
                await this.$axios.delete(`/lists/${list.id}`);
                await this.loadLists();
            } catch (error) {
                console.error(error);
            }
        },
        startEdit(list) {
            list.isEditing = true;
        },
        async saveList(list) {
            try {
                await this.$axios.put(`/lists/${list.id}`, {
                    name: list.newName,
                });
                list.isEditing = false;
                await this.loadLists();
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>
