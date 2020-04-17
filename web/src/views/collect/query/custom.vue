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
    <div class="tex">可以放微信号手机号等</div>
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
