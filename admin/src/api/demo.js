import request from '@/utils/request'

// function getList(params) {
//   return request({
//     url: '/vue-admin-template/table/list',
//     method: 'get',
//     params
//   })
// }

const getList = (params) => request({
  url: '/vue-admin-template/table/list',
  method: 'get',
  params
})

export default {
  getList
}
