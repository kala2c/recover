import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */
export const asyncRoutes = [
  {
    path: '/depot',
    component: Layout,
    redirect: '/depot/index',
    name: 'Depot',
    meta: {
      title: '回收站点管理',
      icon: 'nested',
      roles: ['super', 'admin']
    },
    children: [
      {
        path: '/depot/index',
        component: () => import('@/views/depot/index'),
        meta: { title: '站点信息', roles: ['super', 'admin'] }
      }
    ]
  },
  {
    path: '/admin',
    component: Layout,
    redirect: '/admin/index',
    name: 'Admin',
    meta: {
      title: '负责人管理',
      icon: 'user',
      roles: ['super']
    },
    children: [
      {
        path: 'index',
        component: () => import('@/views/admin/index'),
        meta: { title: '负责人信息', roles: ['super'] }
      }
    ]
  },
  {
    path: '/banner',
    component: Layout,
    redirect: '/banner/index',
    name: 'Banner',
    meta: {
      title: '展示信息',
      icon: 'eye',
      roles: ['super']
    },
    children: [
      {
        path: 'index',
        component: () => import('@/views/banner/index'),
        meta: { title: '轮播图', roles: ['super'] }
      }
      // {
      //   path: 'custom',
      //   component: () => import('@/views/banner/custom'),
      //   meta: { title: '客服信息', roles: ['super'] }
      // }
    ]
  },
  // {
  //   path: '/pickman',
  //   component: Layout,
  //   redirect: '/pickman/index',
  //   name: 'Pickman',
  //   meta: {
  //     title: '取货员管理',
  //     icon: 'nested',
  //     roles: ['super']
  //   },
  //   children: [
  //     {
  //       path: '/index',
  //       component: () => import('@/views/pickman/index'),
  //       meta: { title: '取货员信息', roles: ['super'] }
  //     }
  //     // {
  //     //   path: '/apply',
  //     //   component: () => import('@/views/pickman/apply'),
  //     //   meta: { title: '取货员申请', roles: ['super'] }
  //     // }
  //   ]
  // },
  // {
  //   path: '/city',
  //   component: Layout,
  //   redirect: '/city/index',
  //   name: 'City',
  //   meta: {
  //     title: '城市管理',
  //     icon: 'table',
  //     roles: ['super']
  //   },
  //   children: [
  //     {
  //       path: 'index',
  //       component: () => import('@/views/city/index'),
  //       meta: { title: '城市管理', roles: ['super'] }
  //     }
  //   ]
  // },
  {
    path: '/waste',
    component: Layout,
    meta: { title: '废品管理', icon: 'example', roles: ['super'] },
    children: [
      {
        path: 'index',
        name: 'Waste',
        component: () => import('@/views/waste/index'),
        meta: { title: '废品种类', icon: 'table' }
      }
    ]
  },
  {
    path: '/feedback',
    component: Layout,
    meta: { title: '意见反馈', icon: 'example', roles: ['super'] },
    children: [
      {
        path: 'index',
        name: 'feedback',
        component: () => import('@/views/feedback/index'),
        meta: { title: '意见反馈', icon: 'table' }
      }
    ]
  },
  {
    path: '/area',
    component: Layout,
    // redirect: '/area/index',
    name: 'Area',
    meta: {
      title: '区域管理',
      icon: 'tree',
      roles: ['super', 'admin']
    },
    children: [
      {
        path: 'index',
        component: () => import('@/views/area/index'),
        meta: { title: '区域管理', roles: ['super'] }
      },
      {
        path: 'lowadmin',
        component: () => import('@/views/area/lowadmin'),
        meta: { title: '区域管理', roles: ['admin'] }
      }
    ]
  },
  { path: '*', redirect: '/404', hidden: true }
]
/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },
  {
    path: '/account',
    component: Layout,
    children: [
      {
        path: 'index',
        name: 'Account',
        component: () => import('@/views/account/index'),
        hidden: true
      }
    ]
  },
  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [{
      path: 'dashboard',
      name: 'Dashboard',
      component: () => import('@/views/dashboard/index'),
      meta: { title: '仪表盘', icon: 'dashboard' }
    }]
  },
  {
    path: '/user',
    component: Layout,
    children: [
      {
        path: 'index',
        name: 'User',
        component: () => import('@/views/user/index'),
        meta: { title: '用户信息', icon: 'peoples' }
      }
    ]
  },

  {
    path: '/order',
    component: Layout,
    children: [
      {
        path: 'index',
        name: 'Order',
        component: () => import('@/views/order/index'),
        meta: { title: '订单数据', icon: 'form' }
      }
    ]
  }

  // 404 page must be placed at the end !!!
  // { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
