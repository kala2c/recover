<template>
  <div class="home">
    <h1>接单</h1>
    <foot-bar></foot-bar>
  </div>
</template>

<script>
import FootBar from './components/FootBar'
export default {
  components: {
    FootBar
  },
  methods: {
  },
  mounted() {
  }
}
</script>

<style lang="scss" scoped>
.home {
  box-sizing: border-box;
  height: 100%;
  padding-top: 50px;
  .map {
    height: 100%;
  }
}
</style>
