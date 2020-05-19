import request from '@/utils/request'

const getUserList = (params) => request({
  url: '/user/login/',
  method: 'get',
  params
})
const getUserInfo = (params) => request({
  url: '/user/info/',
  method: 'get',
  params
})

export default {
  getUserList,
  getUserInfo
}
