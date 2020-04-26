import request from '@/utils/request'

const getAreaTable = () => request({
  url: '/admin/area/list'
})

const appendArea = (data) => request({
  url: 'admin/area/append',
  method: 'post',
  data
})

const removeArea = (data) => request({
  url: 'admin/area/remove',
  method: 'post',
  data
})

const setAreaAdmin = (data) => request({
  url: 'admin/area/setAdmin',
  method: 'post',
  data
})

const getAdminList = () => request({
  url: '/admin/area/adminlist'
})

export default {
  getAreaTable,
  appendArea,
  removeArea,
  setAreaAdmin,
  getAdminList
}
