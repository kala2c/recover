// import store from '../store'
import request from '@/utils/request'

const getSdkConf = (url) => request({
  url: 'wx/sdkconf',
  params: {
    url: url
  }
})

const getLocation = () => request({
  url: 'web/user/location'
})

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

const getAreaTable = () => request({
  url: '/collect/area'
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
  getSdkConf,
  getLocation,
  submitFeedback,
  getUserInfo,
  getAddressById,
  getAddressList,
  saveAddress,
  setDefaultAddress,
  getAreaTable,
  getOrderInfo,
  submitOrder,
  getOrderDetail,
  getOrderList
}
