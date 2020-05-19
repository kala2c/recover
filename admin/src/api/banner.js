import request, { baseURL } from '@/utils/request'

function getBannerList(params) {
  return request({
    url: '/admin/banner/list',
    params
  })
}

function addBanner(data) {
  return request({
    url: '/admin/banner/append',
    method: 'post',
    data
  })
}

function delBanner(data) {
  return request({
    url: '/admin/banner/delete',
    method: 'post',
    data
  })
}

const uploadBannerUrl = baseURL + '/admin/banner/append'

export default {
  uploadBannerUrl,
  getBannerList,
  addBanner,
  delBanner
}
