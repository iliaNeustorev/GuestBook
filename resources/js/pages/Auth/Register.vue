<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Регистрация</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <label
                            for="name"
                            class="col-md-4 col-form-label text-md-right"
                            >Имя</label
                        >

                        <div class="col-md-6">
                            <input
                                v-model="name"
                                type="text"
                                class="form-control"
                                required
                                autofocus
                            />
                        </div>
                    </div>

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
                            />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label
                            for="password-confirm"
                            class="col-md-4 col-form-label text-md-right"
                            >Подтвердите пароль</label
                        >

                        <div class="col-md-6">
                            <input
                                v-model="password_confirmation"
                                type="password"
                                class="form-control"
                                required
                            />
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button
                                @click="Registration"
                                type="submit"
                                class="btn btn-primary"
                            >
                                Регистрация
                            </button>
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
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
        };
    },
    mounted() {},
    methods: {
        Registration() {
            const params = {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            };
            axios.post("/api/register", params).then((response) => {
                this.$store.dispatch("getUser", response.data);
                window.history.length > 1
                    ? this.$router.go(-1)
                    : this.$router.push("/");
            });
        },
    },
};
</script>

<style></style>
