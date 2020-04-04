// import store from '../store'
import request from '@/utils/request'

const testApi = (params, data) => request({
  url: '/index/test/test',
  method: 'post',
  params,
  data
})

export default {
  testApi
}
