<template>
  <div class="home">
    <div class="location">定位</div>
    <banner :bannerList="bannerList"></banner>
    <div class="grid">
      <div v-for="item in pageList" :key="item.id" class="grid-item">
        <div class="grid-item-content" @click="toPage(item)">
          <div class="grid-item-pic">
            <img :src="item.icon" alt="" class="icon">
          </div>
          <p class="grid-item-text">{{item.name}}</p>
        </div>
      </div>
      <!-- <div class="grid-item"></div> -->
    </div>
    <div class="rule">
      <div class="rule-item">
        <div class="rule-item-pic">
          <img src="http://static.c2wei.cn/collect-icon/0.png" alt="">
        </div>
        <div class="rule-item-text">
          <span class="strong">拒绝</span>掺水
        </div>
      </div>
      <div class="rule-item">
        <div class="rule-item-pic">
          <img src="http://static.c2wei.cn/collect-icon/1.png" alt="">
        </div>
        <div class="rule-item-text">
          <span class="strong">拒绝</span>掺杂
        </div>
      </div>
      <div class="rule-item">
        <div class="rule-item-pic">
          <img src="http://static.c2wei.cn/collect-icon/2.png" alt="">
        </div>
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
          url: 'http://static.c2wei.cn/banner/banner1.jpeg'
        },
        {
          id: 2,
          url: 'http://static.c2wei.cn/banner/banner.jpeg'
        },
        {
          id: 3,
          url: 'http://static.c2wei.cn/banner/banner1.jpeg'
        }
      ],
      pageList: [
        {
          id: 1,
          path: '',
          icon: 'http://static.c2wei.cn/collect-icon/price.png',
          name: '价格查询'
        },
        {
          id: 2,
          path: '',
          icon: 'http://static.c2wei.cn/collect-icon/city.png',
          name: '服务城市'
        },
        {
          id: 3,
          path: '',
          icon: 'http://static.c2wei.cn/collect-icon/pickman.png',
          name: '附近回收员'
        },
        {
          id: 4,
          path: '',
          icon: 'http://static.c2wei.cn/collect-icon/custom.png',
          name: '联系客服'
        }
      ]
    }
  },
  methods: {
    async getLocation() {
      // const url = encodeURIComponent(location.href.split('#')[0])
      const url = window.location.origin
      const response = await api.getSdkConf(url)
      const data = response.data
      this.$wx.config({
        debug: true,
        appId: data.appId,
        timestamp: data.timestamp,
        nonceStr: data.nonceStr,
        signature: data.signature,
        jsApiList: ['getLocation']
      })
      this.$wx.ready(() => {
        this.$wx.getLocation({
          type: 'gcj102',
          success: function (res) {
            console.log(res, 'location')
            // const latitude = res.latitude
            // const longitude = res.longitude
            // const speed = res.speed
            // const accuracy = res.accuracy
          }
        })
      })
    },
    toPage(item) {
      console.log(item)
    }
  },
  async created() {
    this.getLocation()
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
          .icon {
            display: inline-block;
            box-sizing: border-box;
            width: 40px;
            height: 40px;
            border: 2px solid #eee;
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
        img {
          margin: 10px auto;
          width: 60px;
          height: 60px;
        }
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
