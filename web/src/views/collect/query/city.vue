<template>
  <div class="pickman">
    <van-nav-bar
      title="服务城市"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <p>填写已开放地区和城市</p>
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
