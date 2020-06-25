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
          单次<span class="strong">5KG</span>以上
        </div>
      </div>
    </div>
    <van-grid square>
        <van-grid-item
          v-for="waste in wasteList"
          :key="waste.id"
          icon="photo-o"
          :text="waste.name"
          class="type-grid-item"
          @click="$router.push('/collect/subscribe')"
        />
      </van-grid>
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
import { Icon, Toast, Swipe, SwipeItem, Grid, GridItem } from 'vant'
export default {
  components: {
    VanIcon: Icon,
    // Banner,
    FootBar,
    VanSwipe: Swipe,
    VanSwipeItem: SwipeItem,
    VanGrid: Grid,
    VanGridItem: GridItem
  },
  data() {
    return {
      location: '正在获取位置信息...',
      wasteList: [],
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
          icon: 'http://static.c2wei.cn/collect-icon/price.png',
          name: '价格查询'
        },
        {
          id: 2,
          path: '/collect/query/city',
          icon: 'http://static.c2wei.cn/collect-icon/city.png',
          name: '服务范围'
        },
        {
          id: 3,
          path: '/collect/query/pickman',
          icon: 'http://static.c2wei.cn/collect-icon/pickman.png',
          name: '附近回收点'
        },
        {
          id: 4,
          path: '/collect/query/custom',
          icon: 'http://static.c2wei.cn/collect-icon/custom.png',
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
  .location {
    display: flex;
    align-items: center;
    height: 30px;
    line-height: 30px;
    font-size: 15px;
    background-color: #fff;
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
