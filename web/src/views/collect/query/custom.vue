<template>
  <div class="pickman">
    <van-nav-bar
      title="联系客服"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <div class="text-block">
      <img class="qrcode" src="http://static.c2wei.cn/qr.png" alt="">
      <h4>客服一</h4>
      <br>
      <img class="qrcode" src="http://static.c2wei.cn/qr.png" alt="">
      <h4>客服二</h4>
    </div>
  </div>
</template>

<script>
import { NavBar } from 'vant'
export default {
  components: {
    VanNavBar: NavBar
  }
}
</script>

<style lang="scss" scoped>
.text-block {
  padding: 16px 32px;
  text-align: center;
  .qrcode {
    width: 260px;
  }
}
</style>
