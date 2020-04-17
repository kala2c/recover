<template>
  <div class="map-3d">
    <div id="map"></div>
    <!-- <div class="hide-icon">&copy;</div> -->
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      selfLocation: '37.520755, 121.357534',
      map: null,
      markerLayer: null,
      markerLabel: []
    }
  },
  methods: {
    async initMap() {
      const TMap = window.TMap
      var center = new TMap.LatLng(37.520755, 121.357534)
      const map = new TMap.Map(document.getElementById('map'), {
        center: center, // 设置地图中心点坐标
        zoom: 17.2, // 设置地图缩放级别
        pitch: 43.5, // 设置俯仰角
        rotation: 45 // 设置地图旋转角度
      })
      this.map = map
      this.markerLayer = new TMap.MultiMarker({
        map: map, // 指定地图容器
        // 样式定义
        styles: {
          // 创建一个styleId为'myStyle'的样式（styles的子属性名即为styleId）
          hide: new TMap.MarkerStyle({
            width: 1, // 点标记样式宽度（像素）
            height: 1, // 点标记样式高度（像素）
            src: '/marker.png', // 图片路径
            // 焦点在图片中的像素位置，一般大头针类似形式的图片以针尖位置做为焦点，圆形点以圆心位置为焦点
            anchor: { x: 1, y: 1 }
          })
        },
        // 点标记数据数组
        geometries: []
      })
    },
    async renderMarker(id) {
      const TMap = window.TMap
      const res = await axios({
        url: 'http://map.c2wei.cn/api/buildings?campus_id=1&pt_id=' + id
      })
      const data = res.data.data
      console.log(data)
      // 重置地图上的标记
      // 重置code
      data.forEach(item => {
        const posi = new TMap.LatLng(item.latitude, item.longitude)
        this.markerLayer.add([{
          id: item.id,
          // styleId: 'marker',
          styleId: 'hide',
          position: posi,
          properties: {
            title: item.name
          }
        }])
        this.markerLabel.push({
          id: item.id, // 点图形数据的标志信息
          styleId: 'label', // 样式id
          position: posi, // 标注点位置
          content: item.name // 标注文本
        })
      })
      this.renderLabel(id)
    },
    renderLabel(id) {
      const that = this
      const TMap = window.TMap
      const label = new TMap.MultiLabel({
        id: 'label-layer-' + id,
        map: that.map, // 设置折线图层显示到哪个地图实例中
        // 文字标记样式
        styles: {
          label: new TMap.LabelStyle({
            color: '#666', // 颜色属性
            size: 15, // 文字大小属性
            offset: { x: 0, y: 0 }, // 文字偏移属性单位为像素
            angle: 0, // 文字旋转属性
            alignment: 'center', // 文字水平对齐属性
            verticalAlignment: 'middle' // 文字垂直对齐属性
          })
        },
        // 文字标记数据
        geometries: that.markerLabel
      })
      console.log(label)
    }
  },
  created() {
    console.log(this.$route)
  },
  mounted() {
    this.initMap()
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
