<template>
  <div class="foot-bar">
    <div class="foot-bar-item" :class="{active: 0 === active}">
      <router-link class="link" to="/collect">
        <van-icon name="wap-home" />
      </router-link>
    </div>
    <div class="foot-bar-item" :class="{active: 1 === active}">
      <router-link class="link" to="/collect/shop">
        <van-icon name="shop" />
      </router-link>
    </div>
    <div class="foot-bar-item" :class="{active: 2 === active}">
      <router-link class="link" to="/collect/order">
        <van-icon name="label" />
      </router-link>
    </div>
    <div class="foot-bar-item" :class="{active: 3 === active}">
      <router-link class="link" to="/collect/user">
        <van-icon name="manager" />
      </router-link>
    </div>
  </div>
</template>

<script>
import { Icon } from 'vant'
export default {
  props: {
    active: {
      type: Number,
      default: 0
    }
  },
  components: {
    VanIcon: Icon
  }
}
</script>

<style lang="scss" scoped>
.foot-bar {
  display: flex;
  position: absolute;
  left: 5%;
  bottom: 10px;
  box-sizing: border-box;
  width: 90%;
  height: 40px;
  padding: 0 20px;
  border-radius: 20px;
  background-color: rgba(255, 255, 255, 0.8);
  .foot-bar-item {
    width: 25%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    .link {
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      color: #000;
    }
    &.active {
      .link {
        color: #59c261;
      }
    }
  }
}
</style>
