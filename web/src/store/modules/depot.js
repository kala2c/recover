import api from '@/api/depot'

const state = {
  errMsg: '出错啦',
  info: {
    username: '',
    note: '',
    mobile: '',
    permission: ''
    // status: ''
  }
}

const mutations = {
  SET_ERR_MSG (state, err) {
    state.errMsg = err
  },
  SET_USERNAME (state, name) {
    state.info.username = name
  },
  SET_MOBILE (state, phone) {
    state.info.mobile = phone
  },
  SET_PERMISSION (state, text) {
    state.info.permission = text
  },
  SET_NOTE (state, text) {
    state.info.note = text
  },
  SET_STATUS (state, status) {
    state.info.status = status
  }
}

const actions = {
  setData ({ commit }, data) {
    const keys = Object.keys(data)
    keys.forEach(item => {
      const attr = item.split('_').map(item => item.toUpperCase()).join('_')
      commit('SET_' + attr, data[item])
    })
  },
  clear({ state }) {
    for (const key in state.formData) {
      state.formData[key] = ''
    }
  },
  setErr({ commit }, err) {
    commit('SET_ERR_MSG', err)
  },
  getInfo({ dispatch }) {
    return new Promise((resolve, reject) => {
      api.getInfo().then(response => {
        const { data } = response
        if (!data) {
          reject(new Error('验证失败 将重新登录'))
        }
        console.log(data)
        const info = {
          username: data.username,
          mobile: data.mobile,
          note: data.note
        }
        dispatch('setData', info)
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
