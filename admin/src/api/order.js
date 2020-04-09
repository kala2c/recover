import request from '@/utils/request'

const getOrderList = (params) => request({
  url: '/admin/order/OrderList',
  method: 'get',
  params
})


export default {
  getOrderList
}
