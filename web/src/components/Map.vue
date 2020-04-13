<template>
  <div class="map">2d地图</div>
</template>

<script>
export default {
  methods: {
    // getLocation() {
    //   const qq = window.qq
    //   var map = new qq.maps.Map(document.getElementById('map'), {
    //     // 地图的中心地理坐标。
    //     center: new qq.maps.LatLng(39.916527, 116.397128)
    //   })
    //   console.log('地图对象', map)
    //   const cityLocation = new qq.maps.CityService({
    //     map,
    //     complete(results) {
    //       console.log(111)
    //       // city.style.display = 'inline'
    //       // city.innerHTML = '所在位置: ' + results.detail.name
    //       console.log('结果', results)
    //       map.setCenter(results.detail.latLng)
    //       // const marker = new qq.maps.Marker({
    //       //   map: map,
    //       //   position: results.detail.latLng
    //       // })
    //       // console.log(marker)
    //     },
    //     error(err) {
    //       console.log(err)
    //     }
    //   })
    //   cityLocation.search()
    //   console.log('城市定位', cityLocation)
    // },
  }
}
</script>
