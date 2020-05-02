import request from '@/utils/request'

function getList(params) {
  return request({
    url: '/admin/city/list',
    params
  })
}

function append(data) {
  return request({
    url: '/admin/city/append',
    method: 'post',
    data
  })
}

function setInfo(data) {
  return request({
    url: '/admin/city/setInfo',
    method: 'post',
    data
  })
}

export default {
  getList,
  append,
  setInfo
}
