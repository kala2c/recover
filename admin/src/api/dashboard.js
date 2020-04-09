import request from '@/utils/request'

const dashboard = (params) => request({
  url: '/admin/dashboard',
  method: 'get',
  params
})

export default {
  dashboard
}
