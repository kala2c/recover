<template>
  <div class="order">
    <van-tabs v-model="active" color="#59c261">
      <van-tab
        v-for="tab in tabList"
        :key="tab.id"
        :title="tab.name">
        <div v-if="!!orderList.length" class="list-group">
          <div
            class="list-group-item"
            v-for="order in orderList"
            :key="order.id"
          ></div>
        </div>
        <div v-else class="empty">
          <p>您的{{tab.name}}订单为空</p>
          <p>
            <router-link class="link" to="/collect/subscribe">点我马上下单</router-link>
          </p>
        </div>
      </van-tab>
    </van-tabs>
    <foot-bar></foot-bar>
  </div>
</template>

<script>
import { Tabs, Tab } from 'vant'
import FootBar from '@/views/collect/components/FootBar'
export default {
  components: {
    VanTabs: Tabs,
    VanTab: Tab,
    FootBar
  },
  data() {
    return {
      active: 0,
      tabList: [{
        id: 1,
        name: '待服务'
      }, {
        id: 2,
        name: '服务中'
      }, {
        id: 3,
        name: '已完成'
      }],
      orderList: [
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
.order {
  .empty {
    line-height: 1.5;
    text-align: center;
    padding: 20px 0;
    .link {
      color: #1e9fff;
    }
  }
}
</style>
