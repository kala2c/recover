<template>
  <div class="foot-bar">
    <div
      v-for="item in tabList"
      :key="item.id"
      class="foot-bar-item"
      :class="{active: $route.path === item.path}">
      <router-link class="link" :to="item.path">
        <van-icon :name="item.icon" />
      </router-link>
      <p class="text">{{item.name}}</p>
    </div>
  </div>
</template>

<script>
import { Icon } from 'vant'
export default {
  components: {
    VanIcon: Icon
  },
  data() {
    return {
      tabList: [{
        id: 1,
        name: '首页',
        icon: 'wap-home',
        path: '/collect'
      }, {
        id: 2,
        name: '积分商城',
        icon: 'shop',
        path: '/collect/shop'
      }, {
        id: 3,
        name: '我的订单',
        icon: 'label',
        path: '/collect/order'
      }, {
        id: 4,
        name: '个人中心',
        icon: 'manager',
        path: '/collect/user'
      }]
    }
  }
}
</script>

<style lang="scss" scoped>
.foot-bar {
  display: flex;
  position: absolute;
  // left: 5%;
  bottom: 0;
  box-sizing: border-box;
  width: 100%;
  height: 50px;
  // padding: 0 20px;
  // border-radius: 20px;
  border-top: 1px solid #eee;
  background-color: #EEFAEC;
  // background-color: rgba(238, 250, 236);
  .foot-bar-item {
    width: 25%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    // align-items: center;
    .link {
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      color: #000;
    }
    &.active {
      .link, .text {
        color: #59c261;
      }
    }
    .text {
      text-align: center;
      font-size: 12px;
    }
  }
}
</style>
