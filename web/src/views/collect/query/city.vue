<template>
  <div class="pickman">
    <van-nav-bar
      title="服务城市"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <div class="text-block">
      <h4 style="padding-bottom: 18px;">目前已开放烟台多个地区</h4>
      <list :tree="areaList"></list>
    </div>
  </div>
</template>

<script>
import { NavBar } from 'vant'
import api from '@/api/collect'
import List from '../components/list'
export default {
  components: {
    VanNavBar: NavBar,
    List
  },
  data() {
    return {
      areaList: []
    }
  },
  created() {
    api.getAreaList().then(response => {
      this.areaList = response.data
      console.log(this.areaList)
    })
  }
}
</script>

<style scoped>
.text-block {
  padding: 15px 32px;
}
.group {
  padding-left: 15px;
}
</style>
