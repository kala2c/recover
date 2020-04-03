
const state = {
  goodsList: []
}

const mutations = {
  addGoods (state, goodsData) {
    let goodsId = -1
    state.goodsList.forEach(goods => {
      if (goods.id === goodsData.id) {
        goods.count += 1
        goodsId = goods.id
      }
    })
    if (goodsId < 0) {
      goodsData.count = 1
      state.goodsList.push(goodsData)
    }
  },
  delGoods (state, goodsData) {
    let goodsIndex = -1
    state.goodsList.forEach((goods, index) => {
      if (goods.id === goodsData.id) {
        goods.count -= 1
        if (goods.count < 1) {
          goodsIndex = index
        }
      }
    })
    if (goodsIndex > -1) {
      state.goodsList.splice(goodsIndex, 1)
    }
  }
}

const actions = {
  push ({ commit }, goods) {
    const { id, name, price } = goods
    const goodsData = {
      id,
      name,
      price
    }
    commit('addGoods', goodsData)
  },
  del ({ commit }, goods) {
    commit('delGoods', goods)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
