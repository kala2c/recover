/*
回收下单页面的路由
*/
const routes = [
  {
    path: '/collect',
    component: () => import('@/views/collect/home')
  },
  {
    path: '/collect/subscribe',
    component: () => import('@/views/collect/subscribe')
  },
  {
    path: '/collect/order',
    component: () => import('@/views/collect/order')
  },
  {
    path: '/collect/shop',
    component: () => import('@/views/collect/shop')
  },
  {
    path: '/collect/user',
    component: () => import('@/views/collect/user/index')
  },
  {
    path: '/collect/about',
    component: () => import('@/views/collect/user/about')
  },
  {
    path: '/collect/concat',
    component: () => import('@/views/collect/user/concat')
  },
  {
    path: '/collect/user/feedback',
    component: () => import('@/views/collect/user/feedback')
  },
  {
    path: '/collect/user/address',
    component: () => import('@/views/collect/user/address')
  }
]

export default routes
