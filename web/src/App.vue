<template>
  <div id="app">
    <loading />
    <router-view/>
  </div>
</template>

<script>
import Loading from '@/components/Loading'
export default {
  components: {
    Loading
  },
  mounted() {
    // const VConsole = window.VConsole
    // const vConsole = new VConsole()
    // console.log(vConsole)
  }
}
</script>

<style lang="scss">
* {
  margin: 0;
  padding: 0;
}
html, body {
  position: relative;
  max-width: 500px;
  height: 100%;
  margin: 0 auto;
  overflow: hidden;
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  background-color: #EEFAEC;
  height: 100%;
  overflow: auto;
}
.van-tabs__nav,
.van-panel,
.van-cell-group,
.van-address-item,
.van-nav-bar, .van-nav-bar__placeholder,
.form-wrap .form-ctrl,
.van-cell {
  background-color: #EFFAEF!important;
}
</style>
