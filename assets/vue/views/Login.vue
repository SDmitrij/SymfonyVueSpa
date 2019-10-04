<template>
    <div>
        <hr>
        <div class="row col">
            <form>
                <div class="form-group">
                    <label>
                        <strong>Login</strong>
                        <input
                            v-model="login"
                            type="text"
                            class="form-control"
                        >
                    </label>
                    <span v-if="hasError">Error</span>
                </div>
                <div class="form-group">
                    <label>
                        <strong>Password</strong>
                        <input
                            v-model="password"
                            type="password"
                            class="form-control"
                        >
                    </label>
                    <div v-if="hasError" class="invalid-feedback">Error</div>
                </div>
                <div class="form-group">
                    <button
                        :disabled="login.length === 0 || password.length === 0 || isLoading"
                        type="button"
                        class="btn btn-primary"
                        @click="performLogin()"
                    >
                        Login
                    </button>
                </div>
            </form>

            <div
                v-if="isLoading"
                class="row col"
            >
                <p>Loading...</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                login: "",
                password: ""
            };
        },
        computed: {
            isLoading() {
                return this.$store.getters["security/isLoading"];
            },
            hasError() {
                return this.$store.getters["security/hasError"];
            },
            error() {
                return this.$store.getters["security/error"];
            }
        },
        created() {
            let redirect = this.$route.query.redirect;
            if (this.$store.getters["security/isAuthenticated"]) {
                if (typeof redirect !== "undefined") {
                    this.$router.push({path: redirect});
                } else {
                    this.$router.push({path: "/home"});
                }
            }
        },
        methods: {
            async performLogin() {
                let payload = {login: this.$data.login, password: this.$data.password},
                    redirect = this.$route.query.redirect;
                await this.$store.dispatch("security/login", payload);
                if (!this.$store.getters["security/hasError"]) {
                    if (typeof redirect !== "undefined") {
                        await this.$router.push({path: redirect});
                    } else {
                        await this.$router.push({path: "/home"});
                    }
                }
            }
        }
    }
</script>