<template>
    <div v-if="user">
        <h3 v-if="editors.includes(user.id)" class="text-center mt-5 mb-4">
            Create new task
        </h3>
        <form
            v-if="user && editors.includes(user.id)"
            class="mb-3"
            @submit.prevent="addTask"
        >
            <div class="mb-3">
                <label for="name" class="form-label">Task Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    v-model="newTask.name"
                    placeholder="Enter task name"
                />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input
                    type="text"
                    class="form-control"
                    id="description"
                    v-model="newTask.description"
                    placeholder="Enter task description"
                />
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input
                    type="file"
                    class="form-control"
                    id="image"
                    ref="fileInput"
                    accept="image/png, image/jpg, image/jpeg, image/svg"
                />
            </div>
            <div class="mb-3">
                <label for="tags" class="form-label">Tags</label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control"
                        id="tags"
                        v-model="tagInput"
                        placeholder="Enter tag"
                    />
                    <button
                        @click="addTag"
                        class="btn btn-outline-secondary"
                        type="button"
                    >
                        Add Tag
                    </button>
                </div>
            </div>
            <div v-if="newTask.tags.length > 0" class="mb-3">
                <label class="form-label">Selected Tags</label>
                <ul class="list-group">
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center"
                        v-for="(tag, index) in newTask.tags"
                        :key="index"
                    >
                        {{ tag }}
                        <button
                            @click="removeTag(index)"
                            class="btn btn-danger btn-sm"
                        >
                            Remove
                        </button>
                    </li>
                </ul>
            </div>
            <div class="d-flex justify-content-center mt-3 mb-5">
                <button type="submit" class="btn btn-primary">Add Task</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            newTask: {
                name: "",
                description: "",
                tags: [],
            },
            tagInput: "",
        };
    },
    props: {
        user: {
            type: Object,
            default: null,
        },
        editors: {
            type: Array,
            default: () => [],
        },
    },
    methods: {
        async fetchTasks() {
            this.$emit("fetchTasks");
        },
        async addTask() {
            try {
                let formData = new FormData();
                formData.append("name", this.newTask.name);
                if (this.newTask.description) {
                    formData.append("description", this.newTask.description);
                }
                if (this.$refs.fileInput.files[0]) {
                    formData.append("image", this.$refs.fileInput.files[0]);
                }
                if (this.newTask.tags.length >= 1) {
                    formData.append("tags", JSON.stringify(this.newTask.tags));
                }

                await this.$axios.post(
                    `/tasks/${this.$route.params.id}`,
                    formData
                );

                this.newTask = { name: "", description: "", tags: [] };
                this.$refs.fileInput.value = null;
                this.fetchTasks();
            } catch (error) {
                console.error(error);
            }
        },
        addTag() {
            if (this.tagInput.trim() !== "") {
                this.newTask.tags.push(this.tagInput.trim());
                this.tagInput = "";
            }
        },
        removeTag(index) {
            this.newTask.tags.splice(index, 1);
        },
    },
};
</script>
