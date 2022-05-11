<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <router-link class="navbar-brand" to="/">
                Гостевая книга
            </router-link>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
            >
                <span class="navbar-toggler-icon"> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto"></ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    <li v-if="!user.name" class="nav-item">
                        <router-link class="nav-link" to="/login"
                            >Авторизация</router-link
                        >
                    </li>

                    <li v-if="!user.name" class="nav-item">
                        <router-link class="nav-link" to="/register"
                            >Регистрация</router-link
                        >
                    </li>
                    <li v-if="user.name" class="nav-item dropdown">
                        <span
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                        >
                            {{ user.name }}
                        </span>

                        <div
                            class="dropdown-menu dropdown-menu-right"
                            aria-labelledby="navbarDropdown"
                        >
                            <router-link
                                v-if="checkIsAdmin"
                                class="dropdown-item"
                                to="/admin"
                                >Администрирование</router-link
                            >
                            <button class="dropdown-item" @click="logout()">
                                Выход
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {};
    },
    computed: {
        user() {
            return this.$store.state.user;
        },
        checkIsAdmin() {
            return this.$store.state.checkIsAdmin;
        },
    },
    mounted() {
        if (!this.user.name) {
            axios.get("/api/user").then((response) => {
                this.$store.dispatch("getUser", response.data);
            });
        }

        axios.get("/api/admin/CheckIsAdmin").then((response) => {
            this.$store.dispatch("getcheckIsAdmin", response.data);
        });
    },

    methods: {
        logout() {
            axios.post("/api/logout").then(() => {
                this.$store.dispatch("getUser", {});
                this.$store.dispatch("getcheckIsAdmin", false);
                if (this.$route.path != "/") this.$router.push("/");
            });
        },
    },
};
</script>
