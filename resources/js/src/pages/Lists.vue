<template>
    <div class="container">
        <h2 class="text-center my-5">Lists</h2>
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
            <h2 class="text-center mt-5 mb-3">My Lists</h2>
            <div
                class="card mb-3"
                v-for="(list, index) in myLists"
                :key="index"
            >
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
                    <div class="mt-3">
                        <strong
                            >Editors:
                            {{ getEditorsString(list.editors) }}</strong
                        >
                        <div
                            class="d-flex align-items-center gap-1 flex-wrap"
                            v-for="(editor, index) in list.editors"
                            :key="index"
                        >
                            <span>{{ editor.name }} </span>
                            <button
                                @click="removeEditor(list, editor.id)"
                                class="remove-button"
                            >
                                <p class="remove-text">-</p>
                            </button>
                        </div>
                        <form @submit.prevent="addEditor(list)">
                            <div class="input-group mt-2">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="newEditorEmail"
                                    placeholder="Enter email"
                                />
                                <button class="btn btn-primary" type="submit">
                                    Add
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-3">
                        <strong
                            >Viewers:
                            {{ getViewersString(list.viewers) }}</strong
                        >
                        <div
                            class="d-flex align-items-center gap-1 flex-wrap"
                            v-for="(viewer, index) in list.viewers"
                            :key="index"
                        >
                            <span>{{ viewer.name }} </span>
                            <button
                                @click="removeViewer(list, viewer.id)"
                                class="remove-button"
                            >
                                <p class="remove-text">-</p>
                            </button>
                        </div>
                        <form @submit.prevent="addViewer(list)">
                            <div class="input-group mt-2">
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="newViewerEmail"
                                    placeholder="Enter email"
                                />
                                <button class="btn btn-primary" type="submit">
                                    Add
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div
                v-if="!myLists.length && !editLists.length && !viewLists.length"
                class="alert alert-info"
            >
                No lists found.
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            myLists: [],
            editLists: [],
            viewLists: [],
            loading: false,
            newListName: "",
            newEditorEmail: "",
            newViewerEmail: "",
            lists: [],
        };
    },
    async created() {
        await this.loadLists();
    },
    methods: {
        async loadLists() {
            this.loading = true;
            try {
                const myLists = await this.$axios.get("/lists");
                const editLists = await this.$axios.get("/lists/editable");
                const viewLists = await this.$axios.get("/lists/viewable");

                this.myLists = myLists.data.map((list) => ({
                    ...list,
                    isEditing: false,
                    newName: list.name,
                }));

                this.editLists = editLists.data.map((list) => ({
                    ...list,
                    isEditing: false,
                    newName: list.name,
                }));

                this.viewLists = viewLists.data.map((list) => ({
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
        // Метод для преобразования массива редакторов в строку с именами через запятую
        getEditorsString(editors) {
            return editors.map((editor) => editor.name).join(", ");
        },

        // Метод для преобразования массива читателей в строку с именами через запятую
        getViewersString(viewers) {
            return viewers.map((viewer) => viewer.name).join(", ");
        },
        async addEditor(list) {
            try {
                await this.$axios.post(`/lists/${list.id}/add-editor`, {
                    email: this.newEditorEmail,
                });
                this.newEditorEmail = "";
                this.loadLists();
            } catch (error) {
                console.error(error);
            }
        },
        async addViewer(list) {
            try {
                await this.$axios.post(`/lists/${list.id}/add-viewer`, {
                    email: this.newViewerEmail,
                });
                this.newViewerEmail = "";
                this.loadLists();
            } catch (error) {
                console.error(error);
            }
        },
        async removeEditor(list, editorId) {
            try {
                await this.$axios.delete(
                    `/lists/${list.id}/remove-editor/${editorId}`
                );
                this.loadLists();
            } catch (error) {
                console.error(error);
            }
        },
        async removeViewer(list, viewerId) {
            try {
                await this.$axios.delete(
                    `/lists/${list.id}/remove-viewer/${viewerId}`
                );
                this.loadLists();
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>

<style scoped>
.remove-button {
    margin-right: 10px;
    height: 18px;
    width: 18px;
    border-radius: 0.25rem;
    background-color: red;
    position: relative;
    border: none;
}

.remove-text {
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    line-height: 1px;
    font-size: 28px;

    margin-bottom: 0;
}
</style>
