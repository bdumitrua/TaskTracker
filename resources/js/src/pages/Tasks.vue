<template>
    <div class="container">
        <h2 class="text-center mt-5 mb-4">Tasks List</h2>

        <!-- Список задач -->
        <ul class="list-group">
            <li
                class="list-group-item w-100"
                v-for="task in tasks"
                :key="task.id"
            >
                <div class="d-flex align-items-center">
                    <!-- Превью (thumbnail) задачи -->
                    <div v-if="task.thumbnail" class="me-3">
                        <a target="_blank" :href="getPublicPath(task.image)">
                            <img
                                :src="getPublicPath(task.thumbnail)"
                                alt="Task Thumbnail"
                                style="
                                    width: max(150px, 100%);
                                    height: max(150px, 100%);
                                "
                                class="rounded"
                        /></a>
                    </div>

                    <!-- Информация о задаче -->
                    <div>
                        <h5 class="m-0">{{ task.name }}</h5>
                        <p v-if="task.description">{{ task.description }}</p>
                    </div>

                    <!-- Кнопки редактирования и удаления -->
                    <div class="ms-auto">
                        <button
                            class="btn btn-primary me-2"
                            @click="editTask(task)"
                        >
                            Edit
                        </button>
                        <button
                            class="btn btn-danger"
                            @click="deleteTask(task.id)"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <div v-if="task.tags">
                    <div
                        v-for="(tag, index) in task.tags"
                        :key="index"
                        :class="tagColorClass(tag.name)"
                    >
                        {{ tag.name }}
                    </div>
                </div>
            </li>
            <li
                class="list-unstyled alert alert-info w-100 text-center"
                v-if="tasks.length <= 0"
            >
                There are no tasks yet
            </li>
        </ul>

        <h3 class="text-center mt-5 mb-4">Create new task</h3>
        <form class="mb-3" @submit.prevent="addTask">
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
            tasks: [],
            tagInput: "",
            newTask: {
                name: "",
                description: "",
                tags: [],
            },
            editMode: false,
            editTaskId: null,
        };
    },
    async created() {
        this.fetchTasks();
    },
    methods: {
        async fetchTasks() {
            try {
                const response = await this.$axios.get(
                    `/lists/${this.$route.params.id}`
                );
                this.tasks = [...response.data];
            } catch (error) {
                if (
                    error.response.data == "Unauthorized" ||
                    error.response.status == 403
                ) {
                    return this.$router.push("/404");
                }
            }
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

                const response = await this.$axios.post(
                    `/tasks/${this.$route.params.id}/tasks`,
                    formData
                );
                console.log(response);
                this.newTask = { name: "", description: "", tags: [] };
                this.$refs.fileInput.value = null;
                this.fetchTasks();
            } catch (error) {
                console.error(error);
            }
        },
        async deleteTask(id) {
            try {
                await this.$axios.delete(`/tasks/${id}`);
                this.fetchTasks();
            } catch (error) {
                console.error(error);
            }
        },
        editTask(task) {
            this.editMode = true;
            this.newTask = { ...task };
            this.editTaskId = task.id;
        },
        async saveTask() {
            try {
                await this.$axios.put(
                    `/tasks/${this.editTaskId}`,
                    this.newTask
                );
                this.editMode = false;
                this.newTask = { name: "", description: "" };
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
        tagColorClass(tagName) {
            const firstLetter = tagName.charAt(0).toUpperCase();
            return `badge rounded px-3 py-2 mt-2 me-2 bg-${this.getColorForLetter(
                firstLetter
            )}`;
        },
        getColorForLetter(letter) {
            // Здесь вы можете определить логику для определения цвета на основе буквы
            // Например, можно использовать switch или какой-то объект с соответствиями букв и цветов
            // В этом примере используется случайное присвоение цвета
            const colors = [
                "primary",
                "success",
                "danger",
                "warning",
                "info",
                "dark",
            ];
            const index = letter.charCodeAt(0) % colors.length;
            return colors[index];
        },

        getPublicPath(imagePath) {
            return `/${imagePath}`;
        },
    },
};
</script>
