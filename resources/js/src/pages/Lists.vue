<template>
    <div class="container pb-5">
        <h2 class="text-center my-5">Lists</h2>
        <ListCreate @fetchMyLists="loadMyLists" />
        <div v-if="myLists.length">
            <ListContainer
                @fetchMyLists="loadMyLists"
                title="My Lists"
                :lists="myLists"
                :loading="loadingMyLists"
            />
        </div>
        <div v-if="editLists.length">
            <ListContainer
                title="Lists i can edit"
                :lists="editLists"
                :loading="loadingEditLists"
            />
        </div>
        <div v-if="viewLists.length">
            <ListContainer
                title="Lists i can view"
                :lists="viewLists"
                :loading="loadingViewLists"
            />
        </div>
        <div v-if="!loadingMyLists && !loadingEditLists && !loadingViewLists">
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
import ListContainer from "@/components/ListContainer.vue";
import ListCreate from "@/components/ListCreate.vue";

export default {
    components: { ListCreate, ListContainer },
    data() {
        return {
            myLists: [],
            loadingMyLists: false,
            editLists: [],
            loadingEditLists: false,
            viewLists: [],
            loadingViewLists: false,
        };
    },
    async created() {
        await this.loadMyLists();
        await this.loadEditLists();
        await this.loadViewLists();
    },
    methods: {
        async loadMyLists() {
            this.loadingMyLists = true;

            try {
                const myLists = await this.$axios.get("/lists");

                this.myLists = myLists.data.map((list) => ({
                    ...list,
                    isEditing: false,
                    newName: list.name,
                }));
            } catch (error) {
                console.error(error);
            } finally {
                this.loadingMyLists = false;
            }
        },
        async loadEditLists() {
            this.loadingEditLists = true;

            try {
                const editLists = await this.$axios.get("/lists/editable");

                this.editLists = editLists.data.map((list) => ({
                    ...list,
                    isEditing: false,
                    newName: list.name,
                }));
            } catch (error) {
                console.error(error);
            } finally {
                this.loadingEditLists = false;
            }
        },
        async loadViewLists() {
            this.loadingViewLists = true;

            try {
                const viewLists = await this.$axios.get("/lists/viewable");

                this.viewLists = viewLists.data.map((list) => ({
                    ...list,
                    isEditing: false,
                    newName: list.name,
                }));
            } catch (error) {
                console.error(error);
            } finally {
                this.loadingViewLists = false;
            }
        },
    },
};
</script>
