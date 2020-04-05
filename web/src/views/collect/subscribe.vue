<template>
  <div class="subscribe">
    <van-nav-bar
      title="预约"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <div class="type"></div>
    <div class="price">
      <p>今日指导价</p>
      <p>{{lowPrice}}-{{highPrice}}元/公斤</p>
    </div>
    <div class="weight">
      <div class="info-box">
        <p>可选择预估重量</p>
        <p>(小于十公斤不保证上门回收)</p>
      </div>
      <div class="weight-grid">
        <div class="weight-grid-item">
          <p class="text">10公斤以下</p>
        </div>
        <div class="weight-grid-item">
          <p class="text">10-25公斤</p>
        </div>
        <div class="weight-grid-item">
          <p class="text">25-50公斤</p>
        </div>
        <div class="weight-grid-item">
          <p class="text">50公斤以上</p>
        </div>
      </div>
    </div>
    <div class="form-wrap">
      <div class="field">
        <label for="">预约时间</label>
      </div>
      <div class="field">
        <label for="">回收地址</label>
      </div>
      <div class="submit-wrap">
        <van-button class="submit-btn" round block type="info" native-type="submit">
          提交
        </van-button>
      </div>
    </div>
  </div>
</template>

<script>
import { NavBar, Button } from 'vant'
export default {
  components: {
    VanNavBar: NavBar,
    // VanField: Field,
    VanButton: Button
  },
  data() {
    return {
      lowPrice: 1.00,
      highPrice: 2.00
    }
  }
}
</script>

<style lang="scss" scoped>
.price {
  width: 85%;
  padding: 25px 0;
  margin: 15px auto;
  box-shadow: 0 2px 2px 0 #eee;
  background-color: #fff;
  line-height: 1.5;
  text-align: center;
}
.weight {
  // height: 200px;
  padding: 15px 0;
  margin-top: 15px;
  background-color: #fff;
  text-align: center;
  .weight-grid {
    display: flex;
    flex-wrap: wrap;
    .weight-grid-item {
      width: 50%;
      .text {
        margin: 10px 15px;
        padding: 20px 0;
        border: 1px solid #ccc;
      }
    }
  }
}
.submit-wrap {
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 10px 0;
  margin-top: 16px;
  .submit-btn {
    width: 95%;
    margin: 0 auto;
  }
}
</style>
