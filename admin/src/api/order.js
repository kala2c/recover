import request from '@/utils/request'

const getOrderList = (params) => request({
  url: '/admin/order/OrderList',
  method: 'get',
  params
})

const deleteOrder = (params) => request({
  url: '/admin/order/DeleteOrder',
  method: 'post',
  params
})

const completeOrder = (params) => request({
  url: '/admin/order/CompleteOrder',
  method: 'post',
  params
})

export default {
  getOrderList,
  deleteOrder,
  completeOrder
}
