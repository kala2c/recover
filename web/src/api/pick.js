// import store from '../store'
import request from '@/utils/request'

const getInfo = () => request({
  url: '/pickman/info'
})

const signup = (data) => request({
  url: '/pickman/signup',
  method: 'post',
  data: data
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

const deliveredOrder = (data) => request({
  url: '/pick/deliveredorder',
  method: 'post',
  data
})

export default {
  getInfo,
  signup,
  getOrderList,
  getTakeOrderList,
  takeOrder,
  deliveredOrder
}
