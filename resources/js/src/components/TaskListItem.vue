<template>
    <li class="list-group-item w-100">
        <ErrorBadge :errors="errors" />
        <div class="d-flex align-items-center">
            <!-- Превью (thumbnail) задачи -->
            <div v-if="task.thumbnail" class="me-3 w-25">
                <a target="_blank" :href="getPublicPath(task.image)">
                    <img
                        :src="getPublicPath(task.thumbnail)"
                        alt="Task Thumbnail"
                        style="
                            width: min(150px, 100%);
                            height: min(150px, 100%);
                        "
                        class="rounded"
                    />
                </a>
                <button
                    class="btn btn-danger mt-2 ms-2"
                    @click="deleteImage(task.id)"
                    v-if="editing"
                >
                    Remove image
                </button>
            </div>

            <!-- Информация о задаче или инпуты для редактирования -->
            <div class="w-50">
                <h5 v-if="!editing" class="m-0">{{ task.name }}</h5>
                <label v-else class="d-flex align-items-center gap-3 mb-2">
                    <p style="white-space: nowrap">Task name:</p>
                    <input v-model="editedTask.name" class="form-control" />
                </label>

                <p v-if="!editing && task.description">
                    {{ task.description }}
                </p>

                <label v-else class="d-flex align-items-center gap-3 mb-2">
                    <p style="white-space: nowrap">Task description:</p>
                    <textarea
                        v-if="editing"
                        v-model="editedTask.description"
                        class="form-control"
                    ></textarea>
                </label>

                <label
                    v-if="editing"
                    class="d-flex align-items-center gap-3 mb-2"
                >
                    <p style="white-space: nowrap">Task tags:</p>
                    <input
                        v-if="editing"
                        v-model="editedTags"
                        class="form-control"
                        placeholder="Enter tags separated by space"
                    />
                </label>

                <label v-if="editing" class="d-flex gap-3">
                    <p style="white-space: nowrap" class="text-center my-2">
                        New image (optional):
                    </p>
                    <input
                        type="file"
                        class="form-control"
                        id="image"
                        ref="fileInput"
                        accept="image/png, image/jpg, image/jpeg, image/svg"
                        multiple="false"
                    />
                </label>
            </div>

            <!-- Кнопки редактирования и удаления -->
            <div v-if="user" class="ms-auto">
                <div v-if="editors.includes(user.id)">
                    <button
                        v-if="!editing"
                        class="btn btn-primary me-2"
                        @click="startEditing"
                    >
                        Edit
                    </button>
                    <button
                        v-else
                        class="btn btn-success me-2"
                        @click="saveChanges"
                    >
                        Save
                    </button>
                    <button
                        v-if="!editing"
                        class="btn btn-danger"
                        @click="deleteTask(task.id)"
                    >
                        Done
                    </button>
                </div>
            </div>
        </div>

        <div v-if="task.tags && !editing">
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
import ErrorBadge from "./ErrorBadge.vue";

export default {
    data() {
        return {
            editing: false,
            editedTask: {},
            editedTags: "",
            errors: [],
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
        startEditing() {
            // Создаем копию задачи для редактирования
            this.editedTask = { ...this.task };
            // Преобразуем теги в строку через пробел для отображения в инпуте
            this.editedTags = this.task.tags.map((tag) => tag.name).join(" ");
            this.editing = true;
        },
        async deleteImage(taskId) {
            try {
                this.clearErrors();
                await this.$axios.delete(`/tasks/image/${taskId}`);
                this.fetchTasks();
            } catch (error) {
                this.errors = error.response.data.errors;
            }
        },
        async saveChanges() {
            // Преобразуем строку с тегами в массив слов, разделенных пробелами
            const tagsArray = this.editedTags.split(" ");
            // Удаляем пустые теги (если были несколько пробелов между словами)
            const filteredTags = tagsArray.filter((tag) => tag.trim() !== "");
            // Создаем новый массив объектов с тегами для сохранения
            this.editedTask.tags = filteredTags.map((tag) => tag);
            try {
                this.clearErrors();

                let formData = new FormData();
                formData.append("name", this.editedTask.name);
                if (this.editedTask.description) {
                    formData.append("description", this.editedTask.description);
                }
                if (this.$refs.fileInput.files[0]) {
                    formData.append("image", this.$refs.fileInput.files[0]);
                }
                if (this.editedTask.tags.length >= 1) {
                    formData.append(
                        "tags",
                        JSON.stringify(this.editedTask.tags)
                    );
                }
                formData.append("_method", "patch");
                await this.$axios.post(`/tasks/${this.task.id}`, formData);
                this.editing = false;
                this.$refs.fileInput.value = null;
                this.editedTask = {};
                this.editedTags = "";
                this.fetchTasks();
            } catch (error) {
                this.errors = error.response.data.errors;
            }
        },
        async deleteTask(id) {
            try {
                this.clearErrors();
                await this.$axios.delete(`/tasks/${id}`);
                this.fetchTasks();
            } catch (error) {
                this.errors = error.response.data.errors;
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
        clearErrors() {
            this.errors = [];
        },
    },
    components: { ErrorBadge },
};
</script>
