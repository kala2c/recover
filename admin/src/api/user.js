import request from '@/utils/request'

const getUserList = (params) => request({
  url: '/user/login/',
  method: 'get',
  params
})

const lock = (data) => request({
  url: '/admin/user/lock',
  method: 'post',
  data
})

const unlock = (data) => request({
  url: '/admin/user/unlock',
  method: 'post',
  data
})

export default {
  getUserList,
  lock,
  unlock
}
