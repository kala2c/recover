<template>
  <div class="home">
    <map3d class="map"></map3d>
  </div>
</template>

<script>
import Map3d from '@/components/Map3d'
export default {
  components: {
    Map3d
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
