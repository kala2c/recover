import request from '@/utils/request'

const getFeedbackList = (params) => request({
  url: '/admin/feedback/list',
  method: 'get',
  params
})

const deleteFeedback = (params) => request({
  url: '/admin/feedback/delete',
  method: 'post',
  params
})

export default {
  getFeedbackList,
  deleteFeedback
}
