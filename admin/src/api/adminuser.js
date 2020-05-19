import request from '@/utils/request'

function login(data) {
  return request({
    url: '/user/login/',
    method: 'post',
    data
  })
}

function getInfo() {
  return request({
    url: '/user/info/',
    method: 'get'
  })
}

function logout() {
  return request({
    url: '/admin/user/logout',
    method: 'post'
  })
}

export default {
  login,
  getInfo,
  logout
}
