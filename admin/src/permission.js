import router, { constantRoutes } from './router'
import store from './store'
import { Message } from 'element-ui'
import NProgress from 'nprogress' // 进度条
import 'nprogress/nprogress.css' // 进度条样式
import { getToken } from '@/utils/auth' // get token from cookie
import getPageTitle from '@/utils/get-page-title'

NProgress.configure({ showSpinner: false }) // NProgress 配置

let added = false
function addRoutes(accessRoutes) {
  if (!added) {
    router.options.routes = constantRoutes.concat(accessRoutes)
    router.addRoutes(accessRoutes)
    added = true
  }
}

async function initRoutes() {
  if (getToken()) {
    try {
      //   // 获取用户信息 -- 验证token有效性
      await store.dispatch('user/getInfo')
      const { roles } = await store.dispatch('user/getInfo')
      // // generate accessible routes map based on roles
      const accessRoutes = await store.dispatch('permission/generateRoutes', roles)
      // // dynamically add accessible routes
      addRoutes(accessRoutes)
    } catch (error) {
      // token无效 -- 清除token并重定向到登录页
      await store.dispatch('user/resetToken')
      Message.error(error || '需要重新登录')
    }
  }
}
initRoutes()

const whiteList = ['/login'] // 不需要登录权限的页面白名单

router.beforeEach(async(to, from, next) => {
  // start progress bar
  NProgress.start()

  // 设置页面标题
  document.title = getPageTitle(to.meta.title)

  // determine whether the user has logged in
  const hasToken = getToken()

  if (hasToken) {
    if (to.path === '/login') {
      // 如果存在token 说明用户已登录 重定向到主页
      next({ path: '/' })
      NProgress.done()
    } else {
      const hasGetUserInfo = store.getters.name
      if (hasGetUserInfo) {
        next()
      } else {
        try {
          // 获取用户信息 -- 验证token有效性
          await store.dispatch('user/getInfo')
          const { roles } = await store.dispatch('user/getInfo')

          // generate accessible routes map based on roles
          const accessRoutes = await store.dispatch('permission/generateRoutes', roles)
          // dynamically add accessible routes
          addRoutes(accessRoutes)
          next({ ...to, replace: true })
        } catch (error) {
          // token无效 -- 清除token并重定向到登录页
          await store.dispatch('user/resetToken')
          Message.error(error || '需要重新登录')
          next(`/login?redirect=${to.path}`)
          NProgress.done()
        }
      }
    }
  } else {
    /* 没有token */

    if (whiteList.indexOf(to.path) !== -1) {
      // 如果路由在白名单中 直接打开
      next()
    } else {
      // 不在白名单中需要登录权限，跳转到登录页
      next(`/login?redirect=${to.path}`)
      NProgress.done()
    }
  }
})

router.afterEach(() => {
  NProgress.done()
})
