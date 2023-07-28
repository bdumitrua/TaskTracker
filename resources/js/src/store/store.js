import axiosInstance from "@/axios/instance";
import { createStore } from "vuex";

const store = createStore({
    state() {
        return {
            user: null,
            lists: [],
        };
    },
    getters: {
        user: (state) => {
            return state.user;
        },
        lists: (state) => {
            return state.lists;
        },
    },
    actions: {
        async fetchLists({ commit }) {
            try {
                const response = await axiosInstance.get("/lists");
                const lists = response.data.map((list) => ({
                    ...list,
                    isEditing: false,
                    newName: list.name,
                }));

                commit("SET_LISTS", lists);
            } catch (error) {
                console.error("Error fetching lists:", error);
            }
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
            state.lists = lists;
        },
        SET_USER(state, user) {
            state.user = user;
        },
    },
});

export default store;
