import {createRouter, createWebHistory} from "vue-router";
import {nextTick} from "vue";
import routes from "./routes.js";
import store from "../store";

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
})

router.beforeEach((to, from, next) => {
    store.dispatch('auth/fetch').then(() => {
        let isLogin = store.getters['auth/isAuthenticated'];

        if (to.matched.some(record => record.meta.authenticated)) {
            if (!isLogin) {
                next({ name: 'login' })
                return
            }
        }

        if (to.matched.some(record => record.meta.guest)) {
            if (isLogin) {
                next({ name: 'home' })
                return
            }
        }

        next()
    })
})

router.afterEach((to, from) => {
    nextTick(() => {
        document.title = (to.meta['title'] + ' - ' + import.meta.env.VITE_APP_NAME)
    })
})

export default router;