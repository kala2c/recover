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
        name: '订单列表',
        icon: 'wap-home',
        path: '/depot'
      }, {
        id: 2,
        name: '我的订单',
        icon: 'label',
        path: '/depot/order'
      }, {
        id: 3,
        name: '个人中心',
        icon: 'manager',
        path: '/depot/user'
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
  background-color: #fff;
  // background-color: rgba(255, 255, 255, 0.8);
  .foot-bar-item {
    width: 33%;
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
