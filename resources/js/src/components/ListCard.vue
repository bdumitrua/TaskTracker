<template>
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <form @submit.prevent="saveList(this.list)">
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
                            @submit="saveList(this.list)"
                            hidden
                            type="submit"
                        ></button>
                    </form>
                </div>
                <div v-if="isUserListOwner()" class="d-flex gap-3">
                    <button
                        v-if="!list.isEditing"
                        class="btn btn-primary btn-sm"
                        @click="startEdit(this.list)"
                    >
                        Edit
                    </button>
                    <button
                        v-else
                        class="btn btn-success btn-sm"
                        @click="saveList(this.list)"
                    >
                        Save
                    </button>
                    <button
                        class="btn btn-danger btn-sm"
                        @click="deleteList(this.list)"
                    >
                        Delete
                    </button>
                </div>
            </div>
            <ErrorBadge :error="error" />
            <div
                class="d-flex"
                :class="{
                    'flex-column': isUserListOwner(),
                    'gap-5': !isUserListOwner(),
                }"
            >
                <strong class="mt-3"
                    >Editors: {{ getNamesString(this.list.editors) }}</strong
                >
                <div v-if="isUserListOwner()">
                    <div
                        v-for="(editor, index) in this.list.editors"
                        :key="index"
                    >
                        <span
                            v-if="editor.id !== this.list.user_id"
                            class="d-flex align-items-center gap-1 flex-wrap"
                        >
                            <p class="mb-0">{{ editor.name }}</p>
                            <button
                                @click="removeEditor(this.list, editor.id)"
                                class="remove-button"
                            >
                                <p class="remove-text">-</p>
                            </button>
                        </span>
                    </div>
                    <form @submit.prevent="addEditor(this.list)">
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
                <strong class="mt-3"
                    >Viewers: {{ getNamesString(this.list.viewers) }}</strong
                >
                <div v-if="isUserListOwner()">
                    <div
                        v-for="(viewer, index) in this.list.viewers"
                        :key="index"
                    >
                        <span
                            v-if="viewer.id !== this.list.user_id"
                            class="d-flex align-items-center gap-1 flex-wrap"
                        >
                            <p class="mb-0">{{ viewer.name }}</p>
                            <button
                                @click="removeViewer(this.list, viewer.id)"
                                class="remove-button"
                            >
                                <p class="remove-text">-</p>
                            </button>
                        </span>
                    </div>
                    <form @submit.prevent="addViewer(this.list)">
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
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import ErrorBadge from "./ErrorBadge.vue";

export default {
    components: { ErrorBadge },
    data() {
        return {
            newEditorEmail: "",
            newViewerEmail: "",
            error: "",
        };
    },
    props: {
        list: {
            type: Object,
            required: true,
        },
    },
    computed: {
        ...mapGetters(["user"]),
    },
    methods: {
        async fetchMyLists() {
            this.$store.dispatch("fetchMyLists");
        },
        async deleteList(list) {
            try {
                this.clearError();

                await this.$axios.delete(`/lists/${list.id}`);
                await this.fetchMyLists();
            } catch (error) {
                this.error = error.response.data.errors;
            }
        },
        async saveList(list) {
            try {
                this.clearError();

                await this.$axios.put(`/lists/${list.id}`, {
                    name: list.newName,
                });

                list.isEditing = false;
                await this.fetchMyLists();
            } catch (error) {
                this.error = error.response.data.errors["name"][0];
            }
        },

        async addEditor(list) {
            try {
                this.clearError();

                await this.$axios.post(`/lists/${list.id}/add-editor`, {
                    email: this.newEditorEmail,
                });

                this.newEditorEmail = "";
                this.fetchMyLists();
            } catch (error) {
                this.error = error.response.data.errors;
                console.log(this.error);
            }
        },
        async addViewer(list) {
            try {
                this.clearError();

                await this.$axios.post(`/lists/${list.id}/add-viewer`, {
                    email: this.newViewerEmail,
                });

                this.newViewerEmail = "";
                this.fetchMyLists();
            } catch (error) {
                this.error = error.response.data.errors;
            }
        },
        async removeEditor(list, editorId) {
            try {
                this.clearError();

                await this.$axios.delete(
                    `/lists/${list.id}/remove-editor/${editorId}`
                );

                this.fetchMyLists();
            } catch (error) {
                this.error = error.response.data.errors;
            }
        },
        async removeViewer(list, viewerId) {
            try {
                this.clearError();

                await this.$axios.delete(
                    `/lists/${list.id}/remove-viewer/${viewerId}`
                );

                this.fetchMyLists();
            } catch (error) {
                this.error = error.response.data.errors;
            }
        },
        isUserListOwner() {
            if (this.user) {
                return this.list.user_id === this.user.id;
            }

            return false;
        },
        clearError() {
            this.error = "";
        },
        startEdit(list) {
            list.isEditing = true;
        },
        getNamesString(users) {
            return users.map((user) => user.name).join(", ");
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
