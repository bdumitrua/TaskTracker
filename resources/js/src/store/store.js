import axiosInstance from "@/axios/instance";
import { createStore } from "vuex";

async function fetchListsByType(commit, type, mutationType) {
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
            console.log("fetch my lists");
            fetchListsByType(commit, "", "SET_LISTS");
        },
        async fetchEditLists({ commit }) {
            fetchListsByType(commit, "editable", "SET_EDIT_LISTS");
        },
        async fetchViewLists({ commit }) {
            fetchListsByType(commit, "viewable", "SET_VIEW_LISTS");
        },
        async fetchUser({ commit }) {
            try {
                const response = await axiosInstance.get("/auth/user");
                commit("SET_USER", response.data[0]);
            } catch (error) {
                console.error("Error fetching user:", error);
            }
        },
    },
    mutations: {
        SET_LISTS(state, lists) {
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
