<template>
  <div class="layout">
    <keep-alive>
      <router-view></router-view>
    </keep-alive>
  </div>
</template>

<script>
export default {
  created() {
  }
}
</script>

<style lang="scss" scoped>
.layout {
  height: 100%;
}
</style>
