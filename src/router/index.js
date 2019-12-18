import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'

Vue.use(VueRouter);
Vue.use(VueMaterial)

const routes = [
  {
    path: "/",
    name: "home",
    component: Home
  },
  {
    path: "/personenverwaltung",
    name: "personenverwaltung",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/personenverwaltung.vue")
  }
];

const router = new VueRouter({
  routes
});

export default router;
