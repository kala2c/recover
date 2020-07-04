<template>
  <div class="order">
    <van-nav-bar
      title="已处理订单"
      right-text="刷新"
      @click-right="onRefresh"
      fixed
      placeholder
      border
    />
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
        <!-- <template v-if="item.status === 1" #footer>
          <div class="panel-btn-wrap">
            <van-button @click="toNav(item)" size="small" type="info">导航</van-button>
            <van-button @click="onDelivered(item)" size="small" type="primary">送达</van-button>
          </div>
        </template> -->
      </van-panel>
      <div class="load-btn-wrap">
        <van-button
          plain hairline type="primary"
          round block
          :loading="loading"
          :disabled="!hasNext"
          v-if="hasNext"
          loading-text="加载中..."
          @click="loadMore"
        >
          <span>加载更多</span>
        </van-button>
        <span v-else>没有更多了</span>
      </div>
    </van-pull-refresh>
    <div v-show="!orderList.length && !loading" class="empty">
      <p>您的已处理订单为空</p>
    </div>
    <foot-bar></foot-bar>
  </div>
</template>

<script>
import { NavBar, PullRefresh, Button, Panel } from 'vant'
import FootBar from '@/views/depot/components/FootBar'
import api from '@/api/depot'
import store from '@/store'

export default {
  components: {
    VanNavBar: NavBar,
    VanPullRefresh: PullRefresh,
    // VanList: List,
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
      statusTable: null
    }
  },
  computed: {
    activeStatus() {
      // const activeTab = this.tabList[this.active]
      // return activeTab && activeTab.id
      return 3
    },
    statusMsg() {
      return status => {
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
      api.getOrderList({
        page: this.page,
        status: this.activeStatus
      }).then(response => {
        const data = response.data
        store.dispatch('loading/close')
        this.loading = false
        this.statusTable = data.status
        this.concatList(data.list)
        // this.orderList = this.orderList.concat(data.list)
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
    }
  },
  created() {
    this.loadOrderList()
  },
  activated() {
    const refresh = this.$route.query.refresh
    if (refresh === 1) {
      this.list = []
      this.onRefresh()
    }
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
    button {
      margin-left: 10px;
    }
  }
}
.load-btn-wrap {
  height: 40px;
  line-height: 40px;
  padding: 10px 16px;
  font-size: 12px;
  text-align: center;
}
</style>
