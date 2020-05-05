<template>
  <div class="pickman">
    <van-nav-bar
      title="附近回收员"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <div class="text-block">
      <p
        v-for="pickman in list"
        :key="pickman.id"
      >
      {{pickman.realname}}<br>
      负责区域：&nbsp;
      <span v-if="pickman.area.length > 0">
        <span
          v-for="area in pickman.area"
          :key="area.id"
        >{{area.name}}&nbsp;</span>
      </span>
      <span v-else>暂未分配配送区域</span>
      <br><br>
      </p>
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
      list: []
    }
  },
  created() {
    api.getPickmanList().then(response => {
      this.list = response.data
    })
  }
}
</script>

<style lang="scss" scoped>
.text-block {
  padding: 16px 32px;
}
</style>
