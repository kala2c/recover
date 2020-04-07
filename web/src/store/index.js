import Vue from 'vue'
import Vuex from 'vuex'
import cart from './modules/cart'
import loading from './modules/loading'
import user from './modules/user'
Vue.use(Vuex)

const getters = {
  token: state => state.user.token,
  username: state => state.user.name,
  avatar: state => state.user.avatar,
  cartGoodsList: state => state.cart.goodsList,
  loading: state => state.loading.show
}

export default new Vuex.Store({
  getters,
  modules: {
    cart,
    loading,
    user
  }
})
