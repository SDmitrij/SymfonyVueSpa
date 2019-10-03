import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";
import Home from "../views/Home";
import Login from "../views/Login";
import Registration from "../views/Registration";

Vue.use(VueRouter);

let router = new VueRouter({
    mode: "history",
    routes: [
        { path: "/home", component: Home },
        { path: "/login", component: Login },
        { path: "/register", component: Registration },
        { path: "*", redirect: "/home" }
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters["security/isAuthenticated"]) {
            next();
        } else {
            next({
                path: "/login",
                query: { redirect: to.fullPath }
            });
        }
    } else {
        next();
    }
});

export default router;
