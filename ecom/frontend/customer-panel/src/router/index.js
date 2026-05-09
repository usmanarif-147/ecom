import { createRouter, createWebHistory } from 'vue-router'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import Dashboard from '../views/Dashboard.vue'
import Orders from '../views/Orders.vue'
import Favorites from '../views/Favorites.vue'

const routes = [
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
