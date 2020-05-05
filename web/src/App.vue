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
    const VConsole = window.VConsole
    const vConsole = new VConsole()
    console.log(vConsole)
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
  background-color: #f8f9f9;
  height: 100%;
  overflow: auto;
}
</style>
