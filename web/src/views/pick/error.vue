<template>
  <div class="error">
    <p class="message">{{pickmanErr}}</p>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  data() {
    return {
      message: '出错啦'
    }
  },
  computed: {
    ...mapGetters(['pickmanErr'])
  },
  mounted() {
    console.log(this.pickmanErr)
  }
}
</script>

<style lang="scss" scoped>
.error {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
</style>
