<template>
  <div class="home">
    <div class="location">
      <van-icon name="location-o" />
      {{location}}
    </div>
    <!-- <banner :bannerList="bannerList"></banner> -->
    <van-swipe class="banner-swipe" :autoplay="3000" indicator-color="white">
      <van-swipe-item
        v-for="banner in bannerList"
        :key="banner.id"
      >
        <div class="img" :style="'background-image: url('+banner.url+')'"></div>
      </van-swipe-item>
    </van-swipe>
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
    <p class="section-title">回收品类</p>
    <div class="waste-grid">
      <div
        class="waste-grid-item"
        @click="$router.push('/collect/subscribe/1')"
      >
        <div class="waste-grid-item-pic">
          <img class="icon" :src="baseUrl + '/icon/wastes-1.png'" alt="">
        </div>
        <p class="waste-grid-item-text">衣服</p>
      </div>
      <div
        class="waste-grid-item"
        @click="$router.push('/collect/subscribe/2')"
      >
        <div class="waste-grid-item-pic">
          <img class="icon" :src="baseUrl + '/icon/wastes-2.png'" alt="">
        </div>
        <p class="waste-grid-item-text">纸壳</p>
      </div>
      <div
        class="waste-grid-item"
        @click="$router.push('/collect/subscribe/3')"
      >
        <div class="waste-grid-item-pic">
          <img class="icon" :src="baseUrl + '/icon/wastes-plastic.png'" alt="">
        </div>
        <p class="waste-grid-item-text">塑料</p>
      </div>
      <div
        class="waste-grid-item"
        @click="$router.push('/collect/subscribe/4')"
      >
        <div class="waste-grid-item-pic">
          <img class="icon" :src="baseUrl + '/icon/wastes-phone.png'" alt="">
        </div>
        <p class="waste-grid-item-text">数码产品</p>
      </div>
      <div
        class="waste-grid-item"
        @click="$router.push('/collect/subscribe/5')"
      >
        <div class="waste-grid-item-pic">
          <img class="icon" :src="baseUrl + '/icon/wastes-fridge.png'" alt="">
        </div>
        <p class="waste-grid-item-text">家电产品</p>
      </div>
      <div
        class="waste-grid-item"
        @click="$router.push('/collect/subscribe/6')"
      >
        <div class="waste-grid-item-pic">
          <img class="icon" :src="baseUrl + '/icon/wastes-3.png'" alt="">
        </div>
        <p class="waste-grid-item-text">金属产品</p>
      </div>

    </div>
    <p class="section-title">回收要求</p>
    <div class="rule">
      <div class="rule-item">
        <div class="rule-item-pic">
          <img :src="baseUrl + '/icon/rule-1.png'" alt="">
        </div>
        <div class="rule-item-text">
          <span class="strong">拒绝</span>掺水
        </div>
      </div>
      <div class="rule-item">
        <div class="rule-item-pic">
          <img :src="baseUrl + '/icon/rule-2.png'" alt="">
        </div>
        <div class="rule-item-text">
          <span class="strong">拒绝</span>掺杂
        </div>
      </div>
      <div class="rule-item">
        <div class="rule-item-pic">
          <img :src="baseUrl + '/icon/rule-3.png'" alt="">
        </div>
        <div class="rule-item-text">
          单次<span class="strong">3KG</span>以上
        </div>
      </div>
    </div>
    <div class="one-key-btn" @click="$router.push('/collect/subscribe')">
      一键下单
    </div>
    <div id="map"></div>
    <foot-bar></foot-bar>
  </div>
</template>

<script>
// import Banner from '@/components/Banner'
import FootBar from '@/views/collect/components/FootBar'
import api from '@/api/collect'
import sysApi from '@/api/index'
import { Icon, Toast, Swipe, SwipeItem } from 'vant'
import { baseURL } from '@/utils/request'
export default {
  components: {
    VanIcon: Icon,
    // Banner,
    FootBar,
    VanSwipe: Swipe,
    VanSwipeItem: SwipeItem
  },
  data() {
    return {
      location: '正在获取位置信息...',
      wasteList: [],
      baseUrl: baseURL,
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
          path: '/collect/query/price',
          icon: baseURL + '/icon/price.png',
          name: '价格查询'
        },
        {
          id: 2,
          path: '/collect/query/city',
          icon: baseURL + '/icon/city.png',
          name: '服务范围'
        },
        {
          id: 3,
          path: '/collect/query/pickman',
          icon: baseURL + '/icon/pickman.png',
          name: '附近回收点'
        },
        {
          id: 4,
          path: '/collect/query/custom',
          icon: baseURL + '/icon/custom.png',
          name: '联系客服'
        }
      ]
    }
  },
  methods: {
    async getLocation() {
      const wx = window.wx
      const that = this
      await sysApi.getWxsdkConf({
        url: location.href.split('#')[0]
      }).then(res => {
        const data = res.data
        wx.config({
          debug: false,
          appId: data.appId,
          timestamp: data.timestamp,
          nonceStr: data.nonceStr,
          signature: data.signature,
          jsApiList: ['getLocation']
        })
        wx.ready(() => {
          wx.getLocation({
            type: 'gcj02',
            success: function (res) {
              // console.log(res, 'location')
              // const latitude = res.latitude
              // const longitude = res.longitude
              // const speed = res.speed
              // const accuracy = res.accuracy
              const selfLocation = res.latitude + ',' + res.longitude
              api.getTextLoc({
                location: selfLocation
              }).then(response => {
                const data = response.data
                that.location = data.address || data.errMsg || '无法获取位置信息'
              })
            }
          })
        })
      }).catch(res => {
        // console.log(res)
      })
    },
    getBanner() {
      api.getBannerList().then(response => {
        this.bannerList = response.data
        // console.log(this.bannerList)
      })
    },
    toPage(item) {
      const path = item.path
      if (path) {
        this.$router.push(path)
      } else {
        Toast('该功能暂未上线，敬请期待')
      }
    }
  },
  async created() {
    this.getLocation()
    this.getBanner()
    api.getOrderInfo().then(response => {
      const data = response.data
      this.wasteList = data.waste_list
    })
  },
  async mounted() {
    // const res = await api.getLocation()
    // const data = res.data
    // this.location = data.address || data.errMsg || '无法获取位置信息'
  }
}
</script>

<style lang="scss" scoped>
.home {
  padding-bottom: 130px;
  .section-title {
    padding: 5px 20px;
    border-left: 2px solid #07c160;
  }
  .location {
    display: flex;
    align-items: center;
    height: 30px;
    line-height: 30px;
    font-size: 15px;
    // background-color: #fff;
  }
  .banner-swipe {
    .van-swipe-item {
      width: 100%;
      height: 180px;
      .img {
        width: 100%;
        height: 100%;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
      }
    }
  }
  .grid {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    // background-color: #fff;
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
        box-shadow: 1px 2px 2px 0 rgba(66, 66, 66, .17);
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
    display: flex;
    flex-wrap: wrap;
    // background-color: #fff;
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
  .waste-grid {
    display: flex;
    flex-wrap: wrap;
    // margin-left: 15px;
    // margin-right: 15px;
    border-radius: 5px;
    // background-color: rgba(248, 250, 246);
    .waste-grid-item {
      // flex: 1;
      box-sizing: border-box;
      width: 25%;
      height: 120px;
      .waste-grid-item-pic {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        text-align: center;
        .icon {
          margin-top: 17px;
          width: 46px;
          height: 46px;
        }
      }
      .waste-grid-item-text {
        height: 20px;
        line-height: 20px;
        text-align: center;
      }
    }
  }
</style>
