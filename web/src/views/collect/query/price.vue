<template>
  <div class="pickman">
    <van-nav-bar
      title="价格细目"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <div class="text-block">
      <van-cell-group>
        <van-cell
          v-for="item in wasteList"
          :key="item.id"
          :title="item.name">
          <template #default>
            <p class="cell-text right">{{item.price}}/{{item.unit}}</p>
          </template>
        </van-cell>
      </van-cell-group>
    </div>
  </div>
</template>

<script>
import { NavBar, CellGroup, Cell } from 'vant'
import api from '@/api/collect'
import store from '@/store'
export default {
  components: {
    VanNavBar: NavBar,
    VanCellGroup: CellGroup,
    VanCell: Cell
  },
  data() {
    return {
      wasteList: []
    }
  },
  created() {
    store.dispatch('loading/open')
    api.getOrderInfo().then(response => {
      const data = response.data
      this.wasteList = data.waste_list
      store.dispatch('loading/close')
    }).catch(() => {
      store.dispatch('loading/close')
    })
  }
}
</script>

<style lang="scss" scoped>
.cell-text {
  text-align: left;
  color: #323233;
}
.cell-text.right {
  text-align: right;
}
.text-block {
  padding-top: 15px;
}
</style>
