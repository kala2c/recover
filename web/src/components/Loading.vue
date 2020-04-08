<template>
  <div class="loading" v-if="$store.getters.loading">
    <div class="loading-wrap">
      <van-loading class="loading-icon" type="spinner" color="#1989fa" />
    </div>
    <div class="overlay"></div>
  </div>
</template>

<script>
import { Loading } from 'vant'
export default {
  components: {
    VanLoading: Loading
  }
}
</script>

<style lang="scss" scoped>
  .loading {
    position: absolute;
    top: 0;
    z-index: 99;
    width: 100%;
    height: 100%;
    .overlay {
      position: absolute;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(200, 200, 200, .3);
    }
    .loading-icon {
      position: absolute;
      top: 50%;
      left: 50%;
      z-index: 100;
      display: block;
      margin-top: -20px;
      margin-left: -20px;
      opacity: 1;
    }
  }
</style>
