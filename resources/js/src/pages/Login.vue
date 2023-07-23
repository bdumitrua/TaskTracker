<template>
    <div class="container">
        <h2 class="text-center my-5">Login Page</h2>
        <div v-if="error" class="alert alert-danger" role="alert">
            {{ error }}
        </div>
        <form @submit.prevent="handleSubmit">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="text"
                    class="form-control"
                    id="email"
                    v-model="email"
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
                    @click.prevent="handleSubmit"
                >
                    Login
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
            error: "",
        };
    },
    methods: {
        async handleSubmit() {
            try {
                const response = await this.$axios.post("/auth/login", {
                    email: this.email,
                    password: this.password,
                });

                if (response.data.access_token) {
                    localStorage.setItem(
                        "access_token",
                        response.data.access_token
                    );

                    this.$store.dispatch("user", response.data.user);

                    this.$router.push("/lists");
                } else {
                    console.log("Something gone wrond with jwt");
                }
            } catch (error) {
                this.error = error.response.data.error;
            }
        },
    },
};
</script>
