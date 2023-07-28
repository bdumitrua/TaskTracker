<template>
    <div class="container pb-5">
        <h2 class="text-center my-5">Lists</h2>
        <ListCreate />
        <div v-if="myLists.length">
            <ListContainer
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
import { mapGetters } from "vuex";

export default {
    components: { ListCreate, ListContainer },
    data() {
        return {
            loadingMyLists: false,
            loadingEditLists: false,
            loadingViewLists: false,
        };
    },
    computed: {
        ...mapGetters(["myLists", "editLists", "viewLists"]),
    },
};
</script>
