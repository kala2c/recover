import router from './router'
import store from './store'
import { Message } from 'element-ui'
import NProgress from 'nprogress' // 进度条
import 'nprogress/nprogress.css' // 进度条样式
import { getToken } from '@/utils/auth' // get token from cookie
import getPageTitle from '@/utils/get-page-title'

NProgress.configure({ showSpinner: false }) // NProgress 配置

const whiteList = ['/login'] // 不需要登录页面的白名单

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

          next()
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
