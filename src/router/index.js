import Vue from "vue";
import VueRouter from "vue-router";
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

Vue.use(VueRouter);
Vue.use(VueMaterial);
export default new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes: [
        {
            path: "/",
            name: "personenverwaltung",
            // route level code-splitting
            // this generates a separate chunk (about.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () =>
                import(/* webpackChunkName: "about" */ "../views/personenverwaltung.vue")
        },
        {
            path: "/landverwaltung",
            name: "landverwaltung",
            component: () => import("../views/landverwaltung.vue")
        },

        {
            path: "/ortverwaltung",
            name: "ortverwaltung",
            component: () =>
                import("../views/ortverwaltung.vue")
        },
        {
            path: "/personenverwaltung",
            name: "personenverwaltung",
            component: () => import("../views/personenverwaltung.vue")
        },
    ]
});
