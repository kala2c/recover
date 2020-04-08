import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import wx from 'weixin-js-sdk'

// 登录授权
import './permission'
// 微信js-sdk
Vue.prototype.$wx = wx

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
