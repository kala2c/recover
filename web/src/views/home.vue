<template>
  <div class="home">
    <banner></banner>
    <div class="grid">
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">环保行动</p>
      </div>
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">一键下单</p>
      </div>
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">分类查询</p>
      </div>
      <!-- <div class="grid-item"></div> -->
    </div>
    <notice-bar
      class="notice-bar"
      left-icon="volume-o"
      text="手机点一点，闲置物品上门收~"
      color="#1989fa"
      background="#ffffff"
    ></notice-bar>
    <map name="" class="wrap">
      <div class="map-header">附近的环保卫士</div>
      <Map></Map>
    </map>
    <foot-bar :active="0"></foot-bar>
  </div>
</template>

<script>
import { NoticeBar } from 'vant'
import Banner from '@/components/Banner'
// import Notice from '@/components/Notice'
import Map from '@/components/Map'
import FootBar from '@/components/FootBar'
import api from '../api'

export default {
  components: {
    NoticeBar,
    Banner,
    Map,
    FootBar
  },
  async created() {
    const data = await api.submitOrder({ a: 1 }, { b: 2 })
    console.log(data)

    api.submitOrder().then(res => {
      console.log(res)
    })
  }
}
</script>

<style lang="scss" scoped>
.home {
  height: 200px;
  // background-color: $bgcolor;
  .grid {
    display: flex;
    flex-wrap: wrap;
    background-color: #fff;
    .grid-item {
      // flex: 1;
      box-sizing: border-box;
      width: 33%;
      height: 120px;
      padding: 10px 15px;
      .grid-item-pic {
        width: 80px;
        height: 80px;
      }
      .grid-item-text {
        height: 20px;
        line-height: 20px;
        text-align: center;
      }
    }
  }
  .notice-bar {
    margin: 10px 0;
  }
  .map {

  }
}
</style>
