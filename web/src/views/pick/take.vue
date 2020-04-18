<template>
  <div class="take">
    <van-nav-bar
      title="接单"
      fixed
      placeholder
      border
    />
      <!-- left-text="返回"
      left-arrow
      @click-left="$router.go(-1)" -->
    <van-pull-refresh v-show="!!orderList.length" v-model="refreshing" @refresh="onRefresh">
      <van-panel
        class="panel"
        v-for="item in orderList"
        :key="item.id"
        :title="item.address_detail"
        :desc="item.username + ' ' + item.phone"
        :status="statusMsg(item.status)"
      >
        <div class="panel-content">
          <p class="text">时间：{{item.pick_fast ? '尽快上门' : item.pick_time}}</p>
          <p class="text">物品：{{item.waste.name+' | '+item.waste_number+item.waste.unit}}</p>
          <p class="text">备注：{{item.note || '未填写备注'}}</p>
        </div>
        <template #footer>
          <div class="panel-btn-wrap">
            <van-button @click="onTake(item)" :disabled="takeBtnDisabled" size="small" type="primary">接单</van-button>
          </div>
        </template>
      </van-panel>
      <div class="load-btn-wrap">
        <van-button
          plain hairline type="primary"
          round block
          v-if="hasNext"
          :loading="loading"
          :disabled="!hasNext"
          loading-text="加载中..."
          @click="loadMore"
        >
          <span>加载更多</span>
        </van-button>
        <span v-else>没有更多了</span>
      </div>
    </van-pull-refresh>
    <div v-show="!orderList.length && !loading" class="empty">
      <p>当前没有新订单</p>
    </div>
    <foot-bar></foot-bar>
  </div>
</template>

<script>
import { NavBar, PullRefresh, Button, Panel, Toast, Dialog } from 'vant'
import FootBar from '@/views/pick/components/FootBar'
import api from '@/api/pick'
import store from '@/store'

export default {
  components: {
    VanNavBar: NavBar,
    VanPullRefresh: PullRefresh,
    VanButton: Button,
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
      statusTable: null,
      takeBtnDisabled: false
    }
  },
  computed: {
    statusMsg() {
      return (status) => {
        return this.statusTable[status]
      }
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
      api.getTakeOrderList({
        page: this.page,
        status: this.activeStatus
      }).then(response => {
        const data = response.data
        store.dispatch('loading/close')
        this.loading = false
        this.statusTable = data.status
        this.concatList(data.list)
        this.refreshing = false
        console.log(this.orderList)
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
    onTake(order) {
      Dialog.confirm({
        title: '确认接单',
        message: '接单后将无法取消'
      }).then(() => {
        store.dispatch('loading/open')
        this.takeBtnDisabled = true
        api.takeOrder({
          order_id: order.id
        }).then(response => {
          Toast(response.data.message || '接单成功')
          store.dispatch('loading/close')
          setTimeout(() => {
            this.onRefresh()
            this.$router.push({ path: '/pick/order', query: { refresh: 1 } })
          }, 500)
        }).catch(() => {
          this.takeBtnDisabled = false
          store.dispatch('loading/close')
        })
      }).catch(() => {
      })
    }
  },
  created() {
    this.loadOrderList()
  },
  activated() {
    const refresh = this.$route.query.refresh
    if (refresh === 1) {
      this.onRefresh()
    }
  }
}
</script>

<style lang="scss" scoped>
.order-list {
  height: 500px;
}
.take {
  padding-bottom: 60px;
  .empty {
    line-height: 1.5;
    text-align: center;
    padding: 20px 0;
  }
}
.panel {
  margin-top: 10px;
  .panel-content {
    padding: 10px 16px;
    line-height: 1.5;
  }
  .panel-btn-wrap {
    text-align: right;
  }
}
.load-btn-wrap {
  height: 40px;
  line-height: 40px;
  padding: 10px 16px;
  text-align: center;
  color: #666;
}
</style>
