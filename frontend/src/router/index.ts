// Composables
import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/Home.vue';
import UploadView from '../views/Upload.vue';
import route from "@/router/routes";

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import("@/views/Dashboard.vue"),
    meta: {
      auth: true
    }
  },
  {
    path: '/data',
    name: 'data',
    component: () => import("@/views/DataView.vue"),
    meta: {
      auth: true
    }
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

// antes de cada rota vai ser executado...
router.beforeEach(route);

export default router
