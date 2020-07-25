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
        path: 'subscribe/:wastekindid',
        component: () => import('@/views/collect/subscribe'),
        props: true
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
      },
      {
        path: 'query/price',
        component: () => import('@/views/collect/query/price')
      },
      {
        path: 'query/custom',
        component: () => import('@/views/collect/query/custom')
      },
      {
        path: 'query/pickman',
        component: () => import('@/views/collect/query/pickman')
      },
      {
        path: 'query/city',
        component: () => import('@/views/collect/query/city')
      }
    ]
  }
]
export default routes
