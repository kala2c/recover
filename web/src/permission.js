import router from '@/router'
import Qs from 'querystring'
import store from '@/store'
import { getToken, setToken } from '@/utils/auth'
// import { Toast } from 'vant'

// 生成微信授权链接
function createOauthUrl(state) {
  const params = {
    appid: 'wxb06bf22b4b5e6a5d',
    redirect_uri: encodeURI('http://testwx.c2wei.cn/wx/oauth'),
    response_type: 'code',
    scope: 'snsapi_userinfo',
    state: state
  }
  const baseUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize'
  return baseUrl + '?' + Qs.stringify(params) + '#wechat_redirect'
}

// 当前host
// const host = 'http://' + window.location.host
const host = window.location.origin

const href = window.location.href
// let userinfo = null
let token = null
const parseUrl = href.split('?')
if (parseUrl.length > 1) {
  const parseQs = parseUrl[1].split('=')
  if (parseQs[0] === 'token') {
    // userinfo = JSON.parse(decodeURI(parseQs[1]))
    token = parseQs[1]
    setToken(token)
    store.dispatch('user/setToken', token)
  }
}

// const whiteList = []
router.beforeEach(async(to, from, next) => {
  // 设置页面标题
  // document.title = getPageTitle(to.meta.title)
  const path = to.path.split('/')
  const hasToken = getToken()
  let oauthUrl = ''
  if (router.options.mode && router.options.mode === 'history') {
    oauthUrl = createOauthUrl(host + to.path)
  } else {
    oauthUrl = createOauthUrl(host + '/#' + to.path)
  }
  if (hasToken) {
    if (!store.getters.username) {
      try {
        await store.dispatch('user/getInfo')
        next()
      } catch (error) {
        console.log(error)
        // 重定向到微信授权页
        // setTimeout(function() {
        window.open(oauthUrl, '_self')
        // }, 100000)
      }
    }
    // 回收员页面需要进行回收员信息校验
    if (path.length > 1 && path[1] === 'pick') {
      if (path[2] === 'signup' || path[2] === 'error') {
        next()
      } else {
        if (!store.getters.pickman.realname) {
          try {
            await store.dispatch('pickman/getInfo')
            next()
          } catch (error) {
            console.log(error)
            if (error.message === '80005') {
              next('/pick/signup')
              return
            }
            if (error.message === '80006') {
              store.dispatch('pickman/setErr', '账号已封禁')
              next('/pick/error')
              return
            }
            if (error.message === '80007') {
              store.dispatch('pickman/setErr', '账号正在审核中')
              next('/pick/error')
              return
            }
          }
        }
      }
    }
    next()
  } else {
    // 重定向到微信授权页
    window.open(oauthUrl, '_self')
  }
})
