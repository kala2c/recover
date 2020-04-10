<template>
  <div class="order">
    <van-tabs v-model="active" color="#59c261" sticky>
      <van-tab
        v-for="tab in tabList"
        :key="tab.id"
        :title="tab.name">
        <van-pull-refresh v-show="!!orderList.length" v-model="refreshing" @refresh="onRefresh">
          <van-cell
            v-for="item in orderList"
            :key="item.id"
            :title="item.waste.name+' | '+item.waste_number+item.waste.unit"
            :value="item.status"
            :label="item.pickman && item.pickman.name || '回收员未接单'"
          >
          </van-cell>
          <div class="load-btn-wrap">
            <van-button
              plain hairline type="primary"
              round block
              :loading="loading"
              :disabled="!hasNext"
              loading-text="加载中..."
              @click="loadMore"
            >
              <span v-if="hasNext">加载更多</span>
              <span v-else>没有更多了</span>
            </van-button>
          </div>
        </van-pull-refresh>
        <div v-show="!orderList.length" class="empty">
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
import { Tabs, Tab, PullRefresh, Button, Cell } from 'vant'
import FootBar from '@/views/collect/components/FootBar'
import api from '@/api/collect'
import store from '@/store'

export default {
  components: {
    VanTabs: Tabs,
    VanTab: Tab,
    VanPullRefresh: PullRefresh,
    // VanList: List,
    VanButton: Button,
    VanCell: Cell,
    FootBar
  },
  data() {
    return {
      orderList: [],
      loading: false,
      refreshing: false,
      page: 1,
      hasNext: true,
      active: 0,
      tabList: [{
        id: 0,
        name: '待服务'
      }, {
        id: 1,
        name: '服务中'
      }, {
        id: 2,
        name: '已完成'
      }]
    }
  },
  computed: {
    activeStatus() {
      const activeTab = this.tabList[this.active]
      return activeTab && activeTab.id
    }
  },
  watch: {
    active() {
      this.onRefresh()
    }
  },
  methods: {
    onRefresh() {
      this.page = 1
      this.orderList = []
      this.loadOrderList()
    },
    loadMore() {
      this.page += 1
      this.loadOrderList()
    },
    loadOrderList() {
      store.dispatch('loading/open')
      this.loading = true
      api.getOrderList({
        page: this.page,
        status: this.activeStatus
      }).then(response => {
        const data = response.data
        store.dispatch('loading/close')
        this.loading = false
        this.orderList = this.orderList.concat(data.orderList)
        if (this.page >= data.pageMax) {
          this.hasNext = false
        } else {
          this.hasNext = true
        }
      })
    },
    loadTabs() {

    }
  },
  created() {
    this.loadOrderList()
  }
}
</script>

<style lang="scss" scoped>
.order-list {
  height: 500px;
}
.order {
  padding-bottom: 60px;
  .empty {
    line-height: 1.5;
    text-align: center;
    padding: 20px 0;
    .link {
      color: #1e9fff;
    }
  }
}
.load-btn-wrap {
  height: 40px;
  padding: 10px 16px;
}
</style>
