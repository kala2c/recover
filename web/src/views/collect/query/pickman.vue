<template>
  <div class="pickman">
    <van-nav-bar
      title="附近回收员"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <p>附近回收员</p>
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
