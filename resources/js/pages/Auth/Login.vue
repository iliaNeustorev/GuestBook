<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Авторизация</div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label
                            for="email"
                            class="col-md-4 col-form-label text-md-right"
                            >Почта</label
                        >

                        <div class="col-md-6">
                            <input
                                v-model="email"
                                type="email"
                                class="form-control"
                                required
                                autocomplete="email"
                                autofocus
                            />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label
                            for="password"
                            class="col-md-4 col-form-label text-md-right"
                            >Пароль</label
                        >

                        <div class="col-md-6">
                            <input
                                v-model="password"
                                type="password"
                                class="form-control"
                                required
                                autocomplete="current-password"
                            />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input
                                    v-model="rememberMe"
                                    class="form-check-input"
                                    type="checkbox"
                                />

                                <label class="form-check-label" for="remember">
                                    Запомнить меня
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button @click="login()" class="btn btn-primary">
                                Войти
                            </button>
                            <a
                                v-if="routePasswordRequest"
                                class="btn btn-link"
                                :href="routePasswordRequest"
                            >
                                Забыли пароль
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
            rememberMe: false,
            routePasswordRequest: "",
        };
    },
    methods: {
        login() {
            axios.get("/sanctum/csrf-cookie").then((response) => {
                const params = {
                    email: this.email,
                    password: this.password,
                };
                axios.post("/api/login", params).then((response) => {
                    this.$store.dispatch("getUser", response.data);
                    window.history.length > 1
                        ? this.$router.go(-1)
                        : this.$router.push("/");
                    axios.get("/api/admin/CheckIsAdmin").then((response) => {
                        this.$store.dispatch(
                            "changecheckIsAdmin",
                            response.data
                        );
                    });
                });
            });
        },
    },
};
</script>

<style></style>
