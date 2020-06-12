// import store from '../store'
import request from '@/utils/request'

const getInfo = () => request({
  url: '/depot/info'
})

const signin = (data) => request({
  url: '/depot/signin',
  method: 'post',
  data: data
})

const getOrderList = (params) => request({
  url: '/depot/orders',
  params: params
})

const getTakeOrderList = (params) => request({
  url: '/depot/takeorders',
  params: params
})

const takeOrder = (data) => request({
  url: '/depot/order/take',
  method: 'post',
  data: data
})

const deliveredOrder = (data) => request({
  url: '/depot/deliveredorder',
  method: 'post',
  data
})

const navigate = (params) => request({
  url: '/depot/navigate',
  params: params
})

export default {
  getInfo,
  signin,
  getOrderList,
  getTakeOrderList,
  takeOrder,
  deliveredOrder,
  navigate
}
