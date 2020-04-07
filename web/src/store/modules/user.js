import api from '@/api/collect'
import { getToken } from '@/utils/auth'

const getDefaultState = () => {
  return {
    token: getToken(),
    name: '',
    avatar: ''
  }
}

const state = getDefaultState()

const mutations = {
  RESET_STATE: (state) => {
    Object.assign(state, getDefaultState())
  },
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  SET_NAME: (state, name) => {
    state.name = name
  },
  SET_AVATAR: (state, avatar) => {
    state.avatar = avatar
  }
}

const actions = {

  setToken({ commit }, token) {
    commit('SET_TOKEN', token)
  },
  // 设置用户信息
  getInfo({ commit, state }) {
    return new Promise((resolve, reject) => {
      api.getUserInfo().then(response => {
        const { data } = response
        // if (!data) {
        //   reject('验证失败，请重新登录')
        // }
        console.log(data)

        const { nickname, headimgurl } = data

        commit('SET_NAME', nickname)
        commit('SET_AVATAR', headimgurl)
        resolve(data)
      }).catch(error => {
        reject(error)
      })
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
