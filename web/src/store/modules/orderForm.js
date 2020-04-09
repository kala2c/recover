
const state = {
  wasteIndex: 0,
  formData: {
    waste_id: '',
    pick_time: '',
    pick_fast: '',
    address: '',
    username: '',
    phone: '',
    area: '',
    address_detail: '',
    waste_number: '',
    note: ''
  }
}

const mutations = {
  SET_WASTE_INDEX (state, index) {
    state.wasteIndex = index
  },
  SET_WASTE_ID (state, id) {
    state.waste_id = id
  },
  SET_PICK_TIME (state, pickTime) {
    state.formData.pick_time = pickTime
  },
  SET_PICK_FAST (state, pickFast) {
    state.formData.pick_fast = pickFast
  },
  SET_ADDRESS (state, address) {
    state.formData.address = address
  },
  SET_USERNAME (state, username) {
    state.formData.username = username
  },
  SET_PHONE (state, phone) {
    state.formData.phone = phone
  },
  SET_AREA (state, area) {
    state.formData.area = area
  },
  SET_ADDRESS_DETAIL (state, addressDetail) {
    state.formData.address_detail = addressDetail
  },
  SET_WASTE_NUMBER (state, wasteNumber) {
    state.formData.waste_number = wasteNumber
  },
  SET_NOTE (state, note) {
    state.formData.note = note
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
  async setAddress ({ commit, dispatch }, address) {
    // commit('SET_USERNAME', address.name)
    // commit('SET_PHONE', address.phone)
    // commit('SET_AREA', address.area)
    // commit('SET_ADDRESS_DETAIL', address.detail)
    dispatch('setData', {
      username: address.name,
      phone: address.phone,
      area: address.area,
      address_detail: address.detail
    })
  },
  clear({ state }) {
    for (const key in state.formData) {
      state.formData[key] = ''
    }
  },
  setWasteIndex ({ commit }, index) {
    commit('SET_WASTE_INDEX', index)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
