
const state = {
  wasteIndex: 0,
  formData: {
    waste_id: '',
    pick_time: '',
    pick_fast: '',
    address: '',
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
  SET_ADDRESS (state, address) {
    state.formData.address = address
  },
  SET_PICK_TIME (state, pickTime) {
    state.formData.pick_time = pickTime
  },
  SET_PICK_FAST (state, pickFast) {
    state.formData.pick_fast = pickFast
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
