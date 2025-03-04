import { createRouter, createWebHistory } from 'vue-router'
import TableView from '../views/TableView.vue'
import NotFound from '../views/NotFound.vue'

const routes = [
  {
    path: '/',
    redirect: '/table/1' // Редирект на первый стол (для тестирования)
  },
  {
    path: '/table/:id',
    name: 'Table',
    component: TableView,
    props: true
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router 