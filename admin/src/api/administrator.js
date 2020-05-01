import request from '@/utils/request'

function changepassword(data) {
  return request({
    url: '/admin/user/changepassword',
    method: 'post',
    data
  })
}

function getList(params) {
  return request({
    url: '/admin/admin/list',
    params
  })
}

function append(data) {
  return request({
    url: '/admin/admin/append',
    method: 'post',
    data
  })
}

function setInfo(data) {
  return request({
    url: '/admin/admin/setInfo',
    method: 'post',
    data
  })
}

export default {
  changepassword,
  getList,
  append,
  setInfo
}
