import { createRouter, createWebHistory } from 'vue-router'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import AuthLayout from '../layouts/AuthLayout.vue'
import Dashboard from '../views/Dashboard.vue'
import Orders from '../views/Orders.vue'
import Favorites from '../views/Favorites.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'

const routes = [
  {
    path: '/',
    component: AuthLayout,
    children: [
      { path: 'login', name: 'login', component: Login, meta: { title: 'Sign in' } },
      { path: 'register', name: 'register', component: Register, meta: { title: 'Register' } }
    ]
  },
  {
    path: '/',
    component: DashboardLayout,
    children: [
      { path: '', redirect: '/dashboard' },
      { path: 'dashboard', name: 'dashboard', component: Dashboard, meta: { title: 'Dashboard' } },
      { path: 'orders', name: 'orders', component: Orders, meta: { title: 'Orders' } },
      { path: 'favorites', name: 'favorites', component: Favorites, meta: { title: 'Favorites' } }
    ]
  },
  { path: '/:pathMatch(.*)*', redirect: '/dashboard' }
]

export default createRouter({
  history: createWebHistory(),
  routes
})
