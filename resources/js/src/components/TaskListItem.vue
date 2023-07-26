<template>
    <li class="list-group-item w-100">
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
            <div v-if="user" class="ms-auto">
                <div v-if="editors.includes(user.id)">
                    <button
                        class="btn btn-primary me-2"
                        @click="editTask(task)"
                    >
                        Edit
                    </button>
                    <button
                        class="btn btn-success"
                        @click="deleteTask(task.id)"
                    >
                        Done
                    </button>
                </div>
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
</template>

<script>
export default {
    data() {
        return {
            editMode: false,
            editTaskId: null,
        };
    },
    props: {
        task: {
            type: Object,
            required: true,
        },
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
        tagColorClass(tagName) {
            const firstLetter = tagName.charAt(0).toUpperCase();
            return `badge rounded px-3 py-2 mt-2 me-2 bg-${this.getColorForLetter(
                firstLetter
            )}`;
        },
        getColorForLetter(letter) {
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
