<template>
  <div class="pickman">
    <van-nav-bar
      title=""
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <div class="text-block">
      <h4 style="padding-bottom: 18px;">距离您最近的回收点为</h4>
      <van-cell-group>
        <van-cell
          title="手机号">
          <template #default>
            <p class="cell-text right">{{depot.mobile}}</p>
          </template>
        </van-cell>
        <van-cell
          title="小区">
          <template #default>
            <p class="cell-text right">{{depot.area && depot.area.name}}</p>
          </template>
        </van-cell>
        <van-cell
          title="位置信息">
          <template #default>
            <p class="cell-text right">{{depot.note}}</p>
          </template>
        </van-cell>
      </van-cell-group>
    </div>
  </div>
</template>

<script>
import { NavBar } from 'vant'
import api from '@/api/collect'

export default {
  components: {
    VanNavBar: NavBar
  },
  data() {
    return {
      depot: {
      }
    }
  },
  created() {
    api.getSelfCommunity().then(response => {
      this.depot = response.data
    })
  }
}
</script>

<style scoped>
.text-block {
  padding: 15px 32px;
}
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
