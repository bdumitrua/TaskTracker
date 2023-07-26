<template>
    <TaskList
        :tasks="tasks"
        :user="user"
        :editors="editors"
        @fetchTasks="fetchTasks"
    />
</template>

<script>
import TaskList from "@/components/TaskList.vue";

export default {
    components: { TaskList },
    data() {
        return {
            user: null,
            tasks: [],
            editors: [],
            viewers: [],
        };
    },
    async created() {
        await this.fetchTasks();
    },
    methods: {
        async fetchTasks() {
            try {
                const response = await this.$axios.get(
                    `/lists/${this.$route.params.id}`
                );
                const data = [...response.data];
                this.tasks = [...data[0]];

                // Извините за этот ужас)
                const editors = [...data[1]];
                const viewers = [...data[2]];

                this.editors = editors.map((viewer) => {
                    return viewer.id;
                });

                this.viewers = viewers.map((viewer) => {
                    return viewer.id;
                });

                this.user = this.$store.getters.user;
            } catch (error) {
                if (
                    error.response.data == "Unauthorized" ||
                    error.response.status == 401
                ) {
                    return this.$router.push("/404");
                }
            }
        },
    },
};
</script>
