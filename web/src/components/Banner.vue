<template>
  <div class="banner">
    <div class="swiper" :style="{ width: `${bannerCount*100}%`}">
      <div
        v-for="item in bannerList"
        :key="item.id"
        class="swiper-item"
        :style="`background-image: url(${item.url})`"
      ></div>
    </div>
    <div class="bot-list">
      <div
        v-for="(item, index) in bannerList"
        :key="item.id"
        class="bot-list-item"
        :class="{ active: index === activeIndex}"
        @click="switchBanner(index)"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    bannerList: {
      type: Array,
      default: function() {
        return []
      }
    }
  },
  data() {
    return {
      activeIndex: 0,
      bannerElementList: []
    }
  },
  computed: {
    bannerCount() {
      return this.bannerList.length
    }
  },
  methods: {
    nextBanner() {
      if (this.activeIndex >= this.bannerCount - 1) return
      this.activeIndex++
      this.apply()
    },
    prevBanner() {
      if (this.activeIndex <= 0) return
      this.activeIndex--
      this.apply()
    },
    apply() {
      for (let i = 0; i < this.bannerCount; i++) {
        this.bannerItemList[i].style.transform = `translate3d(-${this.activeIndex * 100}%, 0, 0)`
      }
    },
    bindTouch() {
      const app = document.querySelector('#app')
      // 左右滑动效果
      const touch = {}
      app.addEventListener('touchstart', evt => {
        touch.startX = evt.touches[0].clientX
        touch.endX = 0
      })
      app.addEventListener('touchmove', evt => {
        touch.endX = evt.touches[0].clientX
      })
      app.addEventListener('touchend', () => {
        if (!touch.endX || Math.abs(touch.endX - touch.startX) < 10) {
          return
        }
        if (touch.endX < touch.startX) {
          this.nextBanner()
        } else {
          this.prevBanner()
        }
      })
    },
    bindRoll() {
      // 定时轮播
      setInterval(() => {
        if (this.activeIndex >= this.bannerCount - 1) {
          this.activeIndex = 0
        } else {
          this.activeIndex++
        }
        this.apply()
      }, 5000)
    },
    switchBanner(index) {
      this.activeIndex = index
      this.apply()
    }
  },
  mounted() {
    this.bannerItemList = document.querySelectorAll('.swiper-item')
    console.log('测试', this.bannerItemList)
    this.bindTouch()
    this.bindRoll()
  }
}
</script>

<style lang="scss" scoped>
.banner {
  position: relative;
  width: 100%;
  height: 200px;
  overflow: hidden;
  .swiper {
    display: flex;
    height: 100%;
    .swiper-item {
      flex: 1;
      height: 100%;
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      transition: all .6s ease;
    }
  }
  .bot-list {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: 10px;
    display: flex;
    opacity: .5;
    .bot-list-item {
      width: 8px;
      height: 8px;
      margin: 3px;
      border-radius: 50%;
      background-color: #ccc;
      &.active {
        background-color: #333;
      }
    }
  }
}
</style>
