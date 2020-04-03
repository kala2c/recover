<template>
  <div class="user">
    <div class="userinfo">
      <div class="avatar"></div>
      <div class="nickname">远野</div>
    </div>
    <div class="grid">
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">我的积分</p>
      </div>
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">我的订单</p>
      </div>
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">快速下单</p>
      </div>
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">服务范围</p>
      </div>
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">联系我们</p>
      </div>
      <div class="grid-item">
        <div class="grid-item-pic"></div>
        <p class="grid-item-text">意见反馈</p>
      </div>
    </div>
    <foot-bar :active="3"></foot-bar>
  </div>
</template>

<script>
import FootBar from '@/components/FootBar'
export default {
  components: {
    FootBar
  }
}
</script>

<style lang="scss" scoped>
.user {
  .userinfo {
    text-align: center;
    padding-top: 20px;
    .avatar {
      display: block;
      width: 80px;
      height: 80px;
      border: 2px solid #eee;
      border-radius: 50%;
      margin-left: auto;
      margin-right: auto;
    }
    .nickname {
      display: inline-block;
      min-width: 120px;
      height: 30px;
      line-height: 30px;
      border-radius: 15px;
      margin-top: 10px;
      margin-bottom: 20px;
      text-align: center;
      color: #fff;
      background-color: #59c261;
    }
  }
  .grid {
    display: flex;
    flex-wrap: wrap;
    margin-left: 15px;
    margin-right: 15px;
    border-radius: 5px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(25, 31, 37, .12);
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
}
</style>
