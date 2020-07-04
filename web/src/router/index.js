import Vue from 'vue'
import VueRouter from 'vue-router'

import collect from '@/router/collect'
// import pick from '@/router/pick'
import depot from '@/router/depot'

Vue.use(VueRouter)

// 公共路由
const BaseRoutes = [
  {
    path: '/',
    component: () => import('@/views/home')
  }
]

const routes = BaseRoutes.concat(collect, depot)

const router = new VueRouter({
  // mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
