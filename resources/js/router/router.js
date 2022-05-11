import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store/store";

Vue.use(VueRouter);

const Page404 = { template: "<div>Страница не найдена</div>" };

import HomePage from "../pages/Home";
import LoginPage from "../pages/Auth/Login";
import RegisterPage from "../pages/Auth/Register";
import AdminPage from "../pages/Admin/Admin";
import showUserMessages from "../pages/Admin/ShowUserMessages";

const routes = [
    { path: "/", component: HomePage },
    { path: "/login", component: LoginPage },
    { path: "/register", component: RegisterPage },
    { path: "/admin", component: AdminPage, meta: { admin: true } },
    {
        path: "/Admin/ShowUserMessages",
        component: showUserMessages,
        meta: { admin: true },
    },
    { path: "*", redirect: "404" },
    { path: "/404", component: Page404, name: "404" },
];

const router = new VueRouter({
    mode: "history",
    routes,
});

router.beforeEach((to, from, next) => {
    const checkIsAdmin = store.state.checkIsAdmin;
    if (to.matched.some((record) => record.meta.admin)) {
        if (!checkIsAdmin) {
            next({
                path: "/",
                query: { redirect: to.fullPath },
            });

            return;
        }
    }
    next();
});

export default router;
