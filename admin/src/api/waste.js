import request from '@/utils/request'

const getWasteList = (params) => request({
  url: '/admin/waste/WasteList',
  method: 'get',
  params
})
const getWasteInfo = (params) => request({
  url: '/admin/waste/WasteInfo',
  method: 'get',
  params
})
const setWasetInfo = (params) => request({
  url: '/admin/waste/WasteInfo',
  method: 'put',
  params
})

export default {
  getWasteList,
  getWasteInfo,
  setWasetInfo
}
