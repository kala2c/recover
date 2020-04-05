<template>
  <div class="user">
    <div class="userinfo">
      <div class="avatar"></div>
      <div class="nickname">远野</div>
    </div>
    <div class="grid">
      <div
        v-for="item in gridList"
        :key="item.id"
        class="grid-item"
        @click="toPath(item.path)"
        >
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">{{item.name}}</p>
      </div>
    </div>
    <foot-bar></foot-bar>
  </div>
</template>

<script>
import FootBar from '@/views/collect/components/FootBar'
import { Toast } from 'vant'
export default {
  components: {
    FootBar
  },
  data() {
    return {
      gridList: [{
        id: 1,
        icon: '',
        path: '/collect/order',
        name: '我的订单'
      }, {
        id: 2,
        icon: '',
        path: '/collect/shop',
        name: '积分商城'
      }, {
        id: 3,
        icon: '',
        path: false,
        name: '积分账单'
      }, {
        id: 4,
        icon: '',
        path: false,
        name: '我的积分'
      }, {
        id: 5,
        icon: '',
        path: '/collect/user/address',
        name: '收获地址'
      }, {
        id: 6,
        icon: '',
        path: '/collect/user/feedback',
        name: '意见反馈'
      }]
    }
  },
  methods: {
    toPath(path) {
      if (path) {
        this.$router.push(path)
      } else {
        Toast('该功能暂未上线，敬请期待')
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.user {
  .userinfo {
    text-align: center;
    padding-top: 20px;
    .avatar {
      display: block;
      width: 80px;
      height: 80px;
      border: 2px solid #eee;
      border-radius: 50%;
      margin-left: auto;
      margin-right: auto;
    }
    .nickname {
      display: inline-block;
      min-width: 120px;
      height: 30px;
      line-height: 30px;
      border-radius: 15px;
      margin-top: 10px;
      margin-bottom: 20px;
      text-align: center;
      color: #fff;
      background-color: #59c261;
    }
  }
  .grid {
    display: flex;
    flex-wrap: wrap;
    margin-left: 15px;
    margin-right: 15px;
    border-radius: 5px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(25, 31, 37, .12);
    .grid-item {
      // flex: 1;
      box-sizing: border-box;
      width: 33%;
      height: 120px;
      padding: 10px 15px;
      .grid-item-pic {
        width: 80px;
        height: 80px;
      }
      .grid-item-text {
        height: 20px;
        line-height: 20px;
        text-align: center;
      }
    }
  }
}
</style>
