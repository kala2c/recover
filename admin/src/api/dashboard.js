import request from '@/utils/request'

const dashboard = (params) => request({
  url: '/vue-admin-template/table/list',
  method: 'get',
  params
})

export default {
  dashboard
}
