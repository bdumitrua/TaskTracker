<template>
    <div class="container">
        <h2 class="text-center my-5">Login Page</h2>
        <form>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    type="text"
                    class="form-control"
                    id="username"
                    v-model="username"
                />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="password"
                />
            </div>
            <div class="d-flex justify-content-center">
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="login"
                >
                    Login
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            username: "",
            password: "",
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post("/api/auth/login", {
                    username: this.username,
                    password: this.password,
                });

                if (response.data.access_token) {
                    localStorage.setItem(
                        "access_token",
                        response.data.access_token
                    );
                }
            } catch (error) {
                console.error(error);
            }
        },
    },
};
</script>
