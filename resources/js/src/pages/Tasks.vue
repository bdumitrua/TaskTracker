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
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Превью (thumbnail) задачи -->
                    <div class="me-3">
                        <a target="_blank" :href="getPublicPath(task.image)">
                            <img
                                v-if="task.thumbnail"
                                :src="getPublicPath(task.thumbnail)"
                                alt="Task Thumbnail"
                                style="
                                    width: max(150px, 100%);
                                    height: max(150px, 100%);
                                "
                        /></a>
                    </div>

                    <!-- Информация о задаче -->
                    <div>
                        <h5 class="m-0">{{ task.name }}</h5>
                        <p v-if="task.description">{{ task.description }}</p>
                    </div>

                    <!-- Кнопки редактирования и удаления -->
                    <div>
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
            newTask: {
                name: "",
                description: "",
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
                console.error(error);
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

                const response = await this.$axios.post(
                    `/tasks/${this.$route.params.id}/tasks`,
                    formData
                );
                console.log(response);
                this.newTask = { name: "", description: "" };
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
        getPublicPath(imagePath) {
            return `/${imagePath}`;
        },
    },
};
</script>
