import request from '@/utils/request'

const getDepotList = (params) => request({
  url: '/admin/depot/depotList',
  method: 'get',
  params
})
function append(data) {
  return request({
    url: '/admin/depot/append',
    method: 'post',
    data
  })
}
// const setPickmanStatus = (params) => request({
//   url: '/admin/pickman/PickmanStatus',
//   method: 'post',
//   params
// })
const setArea = (data) => request({
  url: '/admin/depot/setArea',
  method: 'post',
  data
})
const searchArea = (params) => request({
  url: '/admin/depot/searcharea',
  method: 'get',
  params
})
export default {
  getDepotList,
  append,
  setArea,
  searchArea
}
