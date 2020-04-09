import request from '@/utils/request'

const getUserList = (params) => request({
  url: '/admin/user/UserList',
  method: 'get',
  params
})
const getUserInfo = (params) => request({
  url: '/admin/user/UserInfo',
  method: 'get',
  params
})

export default {
  getUserList,
  getUserInfo
}
