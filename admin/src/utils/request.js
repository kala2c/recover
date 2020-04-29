import axios from 'axios'
import { MessageBox, Message } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'
import Qs from 'querystring'

// const baseURL = 'http://127.0.0.9/'
const baseURL = 'http://127.0.0.1:7000/'
// const baseURL = '/'
// 创建一个axios实例
const service = axios.create({
  // baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  baseURL, // url = base url + request url
  // withCredentials: true, // 在跨域请求发送cookies
  timeout: 30000 // 请求超时
})

// request interceptor
service.interceptors.request.use(
  config => {
    // 在请求发送前做点事情
    // 修改默认的content-type axios默认会发送json格式
    config.headers['Content-Type'] = 'application/x-www-form-urlencoded'
    // 转换data格式 axios默认发送的为json格式
    config.data = Qs.stringify(config.data)
    if (store.getters.token) {
      // 在请求头中携带token
      // Authorization 是携带token的请求头
      // please modify it according to the actual situation
      config.headers.Authorization = 'bearer ' + getToken()
    }
    return config
  },
  error => {
    // 请求出错时
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
  */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data

    // if the custom code is not 10000, it is judged as an error.
    if (res.code !== 10000) {
      Message({
        message: res.message || 'Error',
        type: 'error',
        duration: 5 * 1000
      })

      // 10006: Unauthorized; 10008: Other clients logged in; 10005: Login expired;
      if (res.code === 10006 || res.code === 10008 || res.code === 10005) {
        // to re-login
        MessageBox.confirm('您的登录已过期，请取消停留在此页面或者重新登录', '退出登录', {
          confirmButtonText: '重新登录',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          store.dispatch('user/resetToken').then(() => {
            location.reload()
          })
        })
      }
      return Promise.reject(new Error(res.message || 'Error'))
    } else {
      return res
    }
  },
  error => {
    console.log('err' + error) // for debug
    Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
