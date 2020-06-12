<template>
  <div class="order">
    <van-tabs v-model="active" color="#59c261" sticky>
      <van-tab
        v-for="tab in tabList"
        :key="tab.id"
        :title="tab.name">
        <van-pull-refresh v-show="!!orderList.length" v-model="refreshing" @refresh="onRefresh">
          <van-panel
            class="panel"
            v-for="item in orderList"
            :key="item.id"
            :title="item.waste.name+' | '+item.waste_number+item.waste.unit"
            :desc="item.pick_fast === '1' ? '尽快上门' : '预约时间:' + item.pick_time"
            :status="statusMsg(item.status)"
          >
            <div class="panel-content">
              <p class="text address">{{item.username}} {{item.phone}}</p>
              <p class="text address">{{item.address_detail}}</p>
              <p class="text note">备注：{{item.note || '未填写备注'}}</p>
            </div>
            <template v-if="!activeStatus" #footer>
              <div class="panel-btn-wrap">
                <van-button @click="onCancel(item)" size="small" type="danger">取消</van-button>
              </div>
            </template>
          </van-panel>
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
        <div v-show="!orderList.length && !loading" class="empty">
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
import { Tabs, Tab, PullRefresh, Button, Panel, Toast, Dialog } from 'vant'
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
    // VanCell: Cell,
    VanPanel: Panel,
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
        id: 3,
        name: '已完成'
      }
      //  {
      //   id: 1,
      //   name: '取货中'
      // }
      ],
      statusTable: null
    }
  },
  computed: {
    activeStatus() {
      const activeTab = this.tabList[this.active]
      return activeTab && activeTab.id
    },
    statusMsg() {
      return status => {
        return this.statusTable[status]
      }
    }
  },
  watch: {
    active() {
      this.orderList = []
      this.onRefresh()
    }
  },
  methods: {
    onRefresh() {
      this.page = 1
      this.refreshing = true
      // this.orderList = []
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
        this.statusTable = data.status
        // this.orderList = this.orderList.concat(data.orderList)
        this.concatList(data.orderList)
        this.refreshing = false
        if (this.page >= data.pageMax) {
          this.hasNext = false
        } else {
          this.hasNext = true
        }
      })
    },
    concatList(list) {
      if (list) {
        if (this.refreshing) this.orderList = []
        this.orderList = this.orderList.concat(list)
      }
    },
    onCancel(order) {
      Dialog.confirm({
        title: '取消接单',
        message: '真的要取消订单吗'
      }).then(() => {
        api.cancelOrder({
          order_id: order.id
        }).then(response => {
          Toast(response.data.message || '取消成功')
          this.onRefresh()
        })
      }).catch(() => {
      })
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
.panel {
  margin-top: 10px;
  // border-bottom: 1px solid #eee;
  .panel-content {
    padding: 10px 16px;
    font-size: 14px;
    line-height: 1.5;
    .address {
      font-size: 12px;
    }
    .note {
      margin-top: 10px;
    }
  }
  .panel-btn-wrap {
    text-align: right;
  }
}
.load-btn-wrap {
  height: 40px;
  padding: 10px 16px;
}
</style>
