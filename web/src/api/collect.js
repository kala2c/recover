// import store from '../store'
import request from '@/utils/request'

const submitFeedback = (data) => request({
  url: '/collect/feedback',
  method: 'post',
  data
})

const getUserInfo = () => request({
  url: '/web/user/info'
})

const getAddressById = (id) => request({
  url: '/collect/address',
  params: {
    id: id
  }
})

const getAddressList = () => request({
  url: '/collect/addresses',
  method: 'get'
})

const saveAddress = (data) => request({
  url: '/collect/address/set',
  method: 'post',
  data
})

const setDefaultAddress = (data) => request({
  url: '/collect/address/default',
  method: 'post',
  data
})

const getOrderInfo = () => request({
  url: '/collect/orderinfo'
})

const submitOrder = (data) => request({
  url: '/collect/order',
  method: 'post',
  data
})

const getOrderDetail = (params) => request({
  url: '/collect/order',
  method: 'get',
  params
})

const getOrderList = (params) => request({
  url: '/collect/orders',
  params
})

export default {
  submitFeedback,
  getUserInfo,
  getAddressById,
  getAddressList,
  saveAddress,
  setDefaultAddress,
  getOrderInfo,
  submitOrder,
  getOrderDetail,
  getOrderList
}
