import request from '@/utils/request'

const getWasteList = (params) => request({
  url: '/admin/waste/getWasteList',
  method: 'get',
  params
})

export default {
  getWasteList
}
