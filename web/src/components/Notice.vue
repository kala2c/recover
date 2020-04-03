<template>
  <div class="notice">
    <div class="icon">
      <van-icon name="volume-o" />
    </div>
    <div class="text">{{message}}</div>
  </div>
</template>

<script>
import { Icon } from 'vant'
export default {
  props: {
    message: {
      type: String,
      default: '无公告'
    }
  },
  components: {
    VanIcon: Icon
  }
}
</script>

<style lang="scss" scoped>
.notice {
  display: flex;
  height: 35px;
  line-height: 35px;
  .icon {
    width: 35px;
    height: 30px;
  }
  .text {
    flex: 1;
    font-size: 14px;
    animation: roll 1s infinite linear;
  }
}
@keyframes roll {
  0% {
    transform: translate3d(0, 0, 0);
  }
  100% {
    transform: translate3d(-100%, 0, 0);
  }
}
</style>
