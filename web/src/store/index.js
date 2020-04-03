import Vue from 'vue'
import Vuex from 'vuex'
import cart from './modules/cart'
import loading from './modules/loading'
Vue.use(Vuex)

const getters = {
  cartGoodsList: state => state.cart.goodsList,
  loading: state => state.loading.show
}

export default new Vuex.Store({
  getters,
  modules: {
    cart,
    loading
  }
})
