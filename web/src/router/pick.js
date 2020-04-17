import Layout from '@/views/pick/layout'
/*
取货跑腿页面的路由
*/
const routes = [
  {
    path: '/pick',
    component: Layout,
    children: [
      {
        path: '/',
        component: () => import('@/views/pick/take')
      },
      {
        path: 'user',
        component: () => import('@/views/pick/user')
      },
      {
        path: 'order',
        component: () => import('@/views/pick/order')
      },
      {
        path: 'signup',
        component: () => import('@/views/pick/signup')
      },
      {
        path: 'navigation',
        component: () => import('@/views/pick/navigation')
      },
      {
        path: 'take',
        component: () => import('@/views/pick/take')
      }
    ]
  }
]

export default routes
