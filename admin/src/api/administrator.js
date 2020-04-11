import request from '@/utils/request'

function changepassword(data) {
  return request({
    url: '/admin/user/changepassword',
    method: 'post',
    data
  })
}
export default {
  changepassword
}
