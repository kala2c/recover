
const state = {
  show: false
}

const mutations = {
  setShow (state, status) {
    state.show = status
  }
}

const actions = {
  open ({ commit }) {
    commit('setShow', true)
  },
  close ({ commit }) {
    commit('setShow', false)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
