import Layout from '@/views/collect/layout'
/*
回收下单页面的路由
*/
const routes = [
  {
    path: '/collect',
    component: Layout,
    children: [
      {
        path: '/collect',
        component: () => import('@/views/collect/home')
      },
      {
        path: 'subscribe',
        component: () => import('@/views/collect/subscribe')
      },
      {
        path: 'order',
        component: () => import('@/views/collect/order')
      },
      {
        path: 'shop',
        component: () => import('@/views/collect/shop')
      },
      {
        path: 'user',
        component: () => import('@/views/collect/user/index')
      },
      {
        path: 'about',
        component: () => import('@/views/collect/user/about')
      },
      {
        path: 'concat',
        component: () => import('@/views/collect/user/concat')
      },
      {
        path: 'user/feedback',
        component: () => import('@/views/collect/user/feedback')
      },
      {
        path: 'user/address',
        component: () => import('@/views/collect/user/address')
      },
      {
        path: 'user/new/address',
        component: () => import('@/views/collect/user/newAddress')
      }
    ]
  }
]
export default routes
