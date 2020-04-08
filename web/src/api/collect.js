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
  url: '/collect/addresses'
})

const saveAddress = (data) => request({
  url: '/collect/address/set',
  method: 'post',
  data
})

const setDefaultAddress = (data) => request({
  url: '/collect/address/setdefault',
  method: 'post',
  data
})

const getOrderInfo = () => request({
  url: '/collect/orderinfo'
})

export default {
  submitOrder,
  submitFeedback,
  getUserInfo,
  getAddressById,
  getAddressList,
  saveAddress,
  setDefaultAddress,
  getOrderInfo
}
