<template>
  <div class="pickman">
    <van-nav-bar
      title="联系客服"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <div class="text-block">
      <div
        class="wrap"
        v-for="custom in customList"
        :key="custom.id"
      >
        <img class="qrcode" :src="custom.url" alt="">
        <h4>{{custom.name}}</h4>
        <br>
      </div>
    </div>
  </div>
</template>

<script>
import { NavBar } from 'vant'
import api from '@/api/collect'
export default {
  components: {
    VanNavBar: NavBar
  },
  data() {
    return {
      customList: [
        {
          id: 1,
          name: '客服一',
          url: 'http://static.c2wei.cn/qr.png'
        }
      ]
    }
  },
  created() {
    api.getCustom().then(response => {
      this.customList = response.data
    })
  }
}
</script>

<style lang="scss" scoped>
.text-block {
  padding: 16px 32px;
  text-align: center;
  .wrap {
    .qrcode {
      width: 260px;
    }
  }
}
</style>
