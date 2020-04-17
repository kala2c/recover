// import store from '../store'
import request from '@/utils/request'

const getInfo = () => request({
  url: '/pickman/info'
})

const signup = (params) => request({
  url: '/pickman/signup',
  method: 'post',
  params: params
})

const getOrderList = (params) => request({
  url: '/pick/orders',
  params: params
})

const getTakeOrderList = (params) => request({
  url: '/pick/takeorders',
  params: params
})

const takeOrder = (data) => request({
  url: '/pick/order/take',
  method: 'post',
  data: data
})

export default {
  getInfo,
  signup,
  getOrderList,
  getTakeOrderList,
  takeOrder
}
