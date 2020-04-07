<template>
  <div class="layout">
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  created() {
    console.log(111)
  }
}
</script>

<style lang="scss" scoped>
.layout {
  height: 100%;
}
</style>
