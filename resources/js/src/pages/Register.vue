<template>
    <div class="container">
        <h2 class="text-center my-5">Registration Page</h2>
        <form @submit.prevent="handleSubmit">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="name"
                    v-model="name"
                />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input
                    type="email"
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
                    Register
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    async created() {
        if (localStorage.getItem("access_token")) {
            this.$router.push("/");
        }
    },
    data() {
        return {
            name: "Dumitru",
            email: "",
            password: "",
        };
    },
    methods: {
        async handleSubmit() {
            const data = {
                name: this.name,
                email: this.email,
                password: this.password,
            };
            try {
                await this.$axios.post("/auth/register", data);

                this.$router.push("/login");
            } catch (error) {
                console.error(error.response.data.message);
            }
        },
    },
};
</script>
