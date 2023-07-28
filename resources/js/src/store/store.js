import axiosInstance from "@/axios/instance";
import { createStore } from "vuex";

async function fetchListsByType(commit, type, mutationType, user) {
    if (user) {
        try {
            const response = await axiosInstance.get(`/lists/${type}`);
            const lists = response.data.map((list) => ({
                ...list,
                isEditing: false,
                newName: list.name,
            }));
            commit(mutationType, lists);
        } catch (error) {
            console.error(`Error fetching ${type} lists:`, error);
        }
    }
}

const store = createStore({
    state() {
        return {
            user: null,
            myLists: [],
            editLists: [],
            viewLists: [],
        };
    },
    getters: {
        user: (state) => {
            return state.user;
        },
        myLists: (state) => {
            return state.myLists;
        },
        editLists: (state) => {
            return state.editLists;
        },
        viewLists: (state) => {
            return state.viewLists;
        },
    },
    actions: {
        async fetchMyLists({ commit }) {
            fetchListsByType(commit, "", "SET_MY_LISTS", this.state.user);
        },
        async fetchEditLists({ commit }) {
            fetchListsByType(
                commit,
                "editable",
                "SET_EDIT_LISTS",
                this.state.user
            );
        },
        async fetchViewLists({ commit }) {
            fetchListsByType(
                commit,
                "viewable",
                "SET_VIEW_LISTS",
                this.state.user
            );
        },
        async fetchAllLists() {
            this.dispatch("fetchMyLists");
            this.dispatch("fetchEditLists");
            this.dispatch("fetchViewLists");
        },
        async getUser({ commit }) {
            const token = localStorage.getItem("access_token");
            if (token) {
                try {
                    const response = await axiosInstance.get("/auth/user");
                    commit("SET_USER", response.data);
                } catch (error) {
                    console.error("Error fetching user:", error);
                }
            }
        },
        setUser({ commit }, user) {
            commit("SET_USER", user);
            this.dispatch("fetchAllLists");
        },
    },
    mutations: {
        SET_MY_LISTS(state, lists) {
            state.myLists = lists;
        },
        SET_USER(state, user) {
            state.user = user;
        },
        SET_EDIT_LISTS(state, lists) {
            state.editLists = lists;
        },
        SET_VIEW_LISTS(state, lists) {
            state.viewLists = lists;
        },
    },
});

export default store;
