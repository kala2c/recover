// import store from '../store'
import request from '@/utils/request'

const testApi = (params, data) => request({
  url: '/index/test/test',
  method: 'post',
  params,
  data
})

const getWxsdkConf = (params) => request({
  url: '/wx/sdkconf',
  params
})

export default {
  testApi,
  getWxsdkConf
}
