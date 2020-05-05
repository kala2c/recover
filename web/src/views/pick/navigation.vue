<template>
  <div class="map-3d">
    <div id="map"></div>
    <!-- <div class="hide-icon">&copy;</div> -->
  </div>
</template>

<script>
import api from '@/api/index'
import pickApi from '@/api/pick'

export default {
  data() {
    return {
      selfLocation: '37.520755,121.357534',
      map: null,
      orderId: null,
      markerLayer: null,
      polylineLayer: null
    }
  },
  methods: {
    async initMap() {
      const TMap = window.TMap
      const latlng = this.selfLocation.split(',')
      const center = new TMap.LatLng(latlng[0], latlng[1])
      const map = new TMap.Map(document.getElementById('map'), {
        center: center, // 设置地图中心点坐标
        zoom: 15 // 设置地图缩放级别
      })
      this.map = map
      // this.markerLayer = new TMap.MultiMarker({
      //   map: map, // 指定地图容器
      //   // 样式定义
      //   styles: {
      //     // 创建一个styleId为'myStyle'的样式（styles的子属性名即为styleId）
      //     hide: new TMap.MarkerStyle({
      //       width: 1, // 点标记样式宽度（像素）
      //       height: 1, // 点标记样式高度（像素）
      //       src: '/marker.png', // 图片路径
      //       // 焦点在图片中的像素位置，一般大头针类似形式的图片以针尖位置做为焦点，圆形点以圆心位置为焦点
      //       anchor: { x: 1, y: 1 }
      //     })
      //   },
      //   // 点标记数据数组
      //   geometries: []
      // })
      this.polylineLayer = new TMap.MultiPolyline({
        id: 'polyline-layer', // 图层唯一标识
        map: map, // 绘制到目标地图
        // 折线样式定义
        styles: {
          style_blue: new TMap.PolylineStyle({
            color: '#3777FF', // 线填充色
            width: 6, // 折线宽度
            borderWidth: 5, // 边线宽度
            borderColor: '#FFF', // 边线颜色
            lineCap: 'round' // 线端头方式
          })
        },
        // 折线数据定义
        geometries: []
      })
    },
    async getLocation() {
      const wx = window.wx
      await api.getWxsdkConf({
        url: location.href.split('#')[0]
      }).then(res => {
        const data = res.data
        wx.config({
          debug: true,
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
              console.log(res, 'location')
              // const latitude = res.latitude
              // const longitude = res.longitude
              // const speed = res.speed
              // const accuracy = res.accuracy
              this.selfLocation = res.latitude + ',' + res.longitude
            }
          })
        })
      }).catch(res => {
        console.log(res)
      })
    },
    navigate() {
      const TMap = window.TMap
      pickApi.navigate({
        id: this.orderId,
        self_pos: this.selfLocation
      }).then(response => {
        const data = response.data
        // 从结果中取出路线坐标串
        const coors = data.result.routes[0].polyline
        const pl = []
        // 坐标解压（返回的点串坐标，通过前向差分进行压缩，因此需要解压）
        var kr = 1000000
        for (let i = 2; i < coors.length; i++) {
          coors[i] = Number(coors[i - 2]) + Number(coors[i]) / kr
        }
        // 将解压后的坐标生成LatLng数组
        for (var i = 0; i < coors.length; i += 2) {
          pl.push(new TMap.LatLng(coors[i], coors[i + 1]))
        }
        // 显示路线
        this.displayPolyline(pl)
      })
    },
    displayPolyline(pl) {
      const that = this
      this.polylineLayer.add({
        id: 'pl_' + that.orderId, // 折线唯一标识，删除时使用
        styleId: 'style_blue', // 绑定样式名
        paths: pl
      })
    }
  },
  created() {
    console.log(this.$route)
    this.orderId = this.$route.query.id
  },
  mounted() {
    this.getLocation()
    this.initMap()
    this.navigate()
  },
  activated() {
    const id = this.$route.query.id
    if (id && id !== this.orderId) {
      this.polylineLayer.remove('pl_' + this.orderId)
      this.orderId = id
      this.getLocation()
      this.navigate()
    }
  }
}
</script>

<style lang='scss' scoped>
.map-3d {
  position: relative;
  height: 100%;
  #map {
    height: 100%;
  }
  .hide-icon {
    position: absolute;
    z-index: 1000;
    bottom: 0;
    width: 100%;
    height: 20px;
    background-color: #fff;
    text-align: center;
  }
}
</style>
