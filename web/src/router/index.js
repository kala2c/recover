import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

// const viewsPath = '../views/'

const routes = [
  {
    path: '/',
    component: () => import('../views/home')
  },
  {
    path: '/subscribe',
    component: () => import('../views/subscribe')
  },
  {
    path: '/order',
    component: () => import('../views/order')
  },
  {
    path: '/user',
    component: () => import('../views/user/index')
  },
  {
    path: '/about',
    component: () => import('../views/user/about')
  },
  {
    path: '/concat',
    component: () => import('../views/user/concat')
  },
  {
    path: '/feedback',
    component: () => import('../views/user/feedback')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
