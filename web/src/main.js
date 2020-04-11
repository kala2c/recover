import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
// 登录授权
import './permission'

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
