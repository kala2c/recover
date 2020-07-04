import Layout from '@/views/depot/layout'
/*
取货跑腿页面的路由
*/
const routes = [
  {
    path: '/depot',
    component: Layout,
    children: [
      {
        path: '/',
        component: () => import('@/views/depot/take')
      },
      {
        path: 'error',
        component: () => import('@/views/depot/error')
      },
      {
        path: 'user',
        component: () => import('@/views/depot/user')
      },
      {
        path: 'order',
        component: () => import('@/views/depot/order')
      },
      {
        path: 'signin',
        component: () => import('@/views/depot/signin')
      },
      {
        path: 'navigation',
        component: () => import('@/views/depot/navigation')
      },
      {
        path: 'take',
        component: () => import('@/views/depot/take')
      }
    ]
  }
]

export default routes
