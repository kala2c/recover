// import store from '../store'
import request from '@/utils/request'

const testApi = (params, data) => request({
  url: '/index/test/test',
  method: 'post',
  params,
  data
})

const getUserInfo = () => request({
  url: '/web/user/info'
})

export default {
  testApi,
  getUserInfo
}
