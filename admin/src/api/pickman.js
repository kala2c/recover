import request from '@/utils/request'

const getPickmanList = (params) => request({
  url: '/admin/pickman/PickmanList',
  method: 'get',
  params
})
const setPickmanStatus = (params) => request({
  url: '/admin/pickman/PickmanStatus',
  method: 'post',
  params
})
export default {
  getPickmanList,
  setPickmanStatus
}
