import request from '@/utils/request'

function login(data) {
  return request({
    url: '/clogin',
    method: 'post',
    data
  })
}

function getInfo() {
  return request({
    url: '/admin/user/info',
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
