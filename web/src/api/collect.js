// import store from '../store'
import request from '@/utils/request'

const submitOrder = (params, data) => request({
  url: '/index/test/test',
  method: 'post',
  params,
  data
})

const submitFeedback = (data) => request({
  url: '/collect/feedback',
  method: 'post',
  data
})

export default {
  submitOrder,
  submitFeedback
}
