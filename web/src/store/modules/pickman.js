import api from '@/api/pick'

const state = {
  errMsg: '出错啦',
  info: {
    realname: '',
    phone: '',
    sex: '',
    age: '',
    status: ''
  }
}

const mutations = {
  SET_ERR_MSG (state, err) {
    state.errMsg = err
  },
  SET_REALNAME (state, name) {
    state.info.realname = name
  },
  SET_PHONE (state, phone) {
    state.info.phone = phone
  },
  SET_SEX (state, sex) {
    state.info.sex = sex
  },
  SET_AGE (state, age) {
    state.info.age = age
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
        const info = {
          realname: data.realname,
          phone: data.phone,
          sex: data.sex,
          age: data.age,
          status: '正式回收员'
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
