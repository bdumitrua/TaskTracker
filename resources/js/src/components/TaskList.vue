<template>
    <div class="container">
        <h2 class="text-center mt-5 mb-4">Tasks List</h2>

        <ul class="list-group">
            <TaskListItem
                v-for="task in tasks"
                :key="task.id"
                :task="task"
                :user="user"
                :editors="editors"
                @fetchTasks="fetchTasks"
            />
            <li
                class="list-unstyled alert alert-info w-100 text-center"
                v-if="tasks.length <= 0"
            >
                There are no tasks yet
            </li>
        </ul>

        <!-- форму сюда -->
        <TaskCreate :user="user" :editors="editors" @fetchTasks="fetchTasks" />
    </div>
</template>

<script>
import TaskCreate from "@/components/TaskCreate.vue";
import TaskListItem from "@/components/TaskListItem.vue";

export default {
    components: {
        TaskListItem,
        TaskCreate,
    },
    props: {
        tasks: {
            type: Array,
            default: () => [],
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
    data() {
        return {};
    },
    methods: {
        async fetchTasks() {
            this.$emit("fetchTasks");
        },
    },
};
</script>
