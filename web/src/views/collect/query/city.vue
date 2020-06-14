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
      <p
        v-for="qu in areaTable"
        :key="qu.id"
      >
      {{ qu.name }}
      <br>
      <span v-if="qu.child.length > 0">
        <span
          v-for="item1 in qu.child"
          :key="item1.id"
        >
        {{item1.name}}
        <!-- <span v-if="item1.child.length > 0">
          <span
            v-for="item2 in item1.child"
            :key="item2.id"
          >
          {{ item2.name }} &nbsp;
          </span> -->
        <!-- </span> -->
        <br>
        </span>
        <br>
      </span>
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
      areaTable: []
    }
  },
  created() {
    api.getAreaTable().then(response => {
      this.areaTable = response.data
    })
  }
}
</script>

<style scoped>
.text-block {
  padding: 15px 32px;
}
</style>
