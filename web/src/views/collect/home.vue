<template>
  <div class="home">
    <div class="location">定位</div>
    <banner :bannerList="bannerList"></banner>
    <div class="grid">
      <div class="grid-item">
        <div class="grid-item-content">
          <div class="grid-item-pic">
            <div class="img"></div>
          </div>
          <p class="grid-item-text">价格查询</p>
        </div>
      </div>
      <div class="grid-item">
        <div class="grid-item-content">
          <div class="grid-item-pic">
            <div class="img"></div>
          </div>
          <p class="grid-item-text">客服电话</p>
        </div>
      </div>
      <div class="grid-item">
        <div class="grid-item-content">
          <div class="grid-item-pic">
            <div class="img"></div>
          </div>
          <p class="grid-item-text">附近回收员</p>
        </div>
      </div>
      <div class="grid-item">
        <div class="grid-item-content">
          <div class="grid-item-pic">
            <div class="img"></div>
          </div>
          <p class="grid-item-text">服务城市</p>
        </div>
      </div>
      <!-- <div class="grid-item"></div> -->
    </div>
    <div class="rule">
      <div class="rule-item">
        <div class="rule-item-pic"></div>
        <div class="rule-item-text">
          <span class="strong">拒绝</span>掺水
        </div>
      </div>
      <div class="rule-item">
        <div class="rule-item-pic"></div>
        <div class="rule-item-text">
          <span class="strong">拒绝</span>掺杂
        </div>
      </div>
      <div class="rule-item">
        <div class="rule-item-pic"></div>
        <div class="rule-item-text">
          单次<span class="strong">10KG</span>以上
        </div>
      </div>
    </div>
    <div class="one-key-btn" @click="$router.push('/collect/subscribe')">
      一键下单
    </div>
    <foot-bar></foot-bar>
  </div>
</template>

<script>
import Banner from '@/components/Banner'
import FootBar from '@/views/collect/components/FootBar'
import api from '@/api/collect'

export default {
  components: {
    Banner,
    FootBar
  },
  data() {
    return {
      bannerList: [
        {
          id: 1,
          url: 'http://127.0.0.1:7000/images/banner1.jpeg'
        },
        {
          id: 2,
          url: 'http://127.0.0.1:7000/images/banner2.jpeg'
        },
        {
          id: 3,
          url: 'http://127.0.0.1:7000/images/banner1.jpeg'
        }
      ]
    }
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
  padding-bottom: 130px;
  .location {
    height: 30px;
    line-height: 30px;
    background-color: #fff;
  }
  .grid {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    background-color: #fff;
    .grid-item {
      // flex: 1;
      box-sizing: border-box;
      width: 50%;
      .grid-item-content {
        margin: 10px 10px;
        padding: 10px 0;
        display: flex;
        align-items: center;
        border: 1px solid #eee;
        border-radius: 5px;
        box-shadow: 2px 4px 4px 0 rgba(33, 33, 33, .17);
        .grid-item-pic {
          width: 40%;
          height: 40px;
          text-align: center;
          .img {
            display: inline-block;
            box-sizing: border-box;
            width: 40px;
            height: 40px;
            border: 2px solid #ccc;
            border-radius: 50%;
          }
        }
        .grid-item-text {
          width: 60%;
          font-size: 14px;
          height: 20px;
          line-height: 20px;
          text-align: center;
        }
      }
    }
  }
  .rule {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    background-color: #fff;
    .rule-item {
      // flex: 1;
      box-sizing: border-box;
      width: 33%;
      padding: 10px 0;
      text-align: center;
      .rule-item-pic {
        display: inline-block;
        width: 80px;
        height: 80px;
      }
      .rule-item-text {
        height: 20px;
        line-height: 20px;
      }
    }
  }
  .one-key-btn {
    position: absolute;
    left: 50%;
    bottom: 50px;
    box-sizing: border-box;
    width: 74px;
    height: 74px;
    padding: 10px;
    border: 5px solid aquamarine;
    border-radius: 50%;
    margin-left: -37px;
    background-color: #59c261;
    text-align: center;
    color: #fff;
  }
  .notice-bar {
    margin: 10px 0;
  }
  .map {
    height: 200px;
  }
}
</style>
