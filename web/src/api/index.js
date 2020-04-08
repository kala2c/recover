// import store from '../store'
import request from '@/utils/request'

const testApi = (params, data) => request({
  url: '/index/test/test',
  method: 'post',
  params,
  data
})

const getWxsdkConf = () => request({
  url: '/wx/sdkconf'
})

export default {
  testApi,
  getWxsdkConf
}
