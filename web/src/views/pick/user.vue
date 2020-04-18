<template>
  <div class="user">
    <div class="userinfo">
      <div class="avatar">
        <img :src="avatar" alt="">
      </div>
      <div class="nickname">{{username}}</div>
    </div>
    <div class="pickman">
      <van-cell-group>
        <van-cell title="姓名：">
          <template #default>
            <p class="cell-text right">{{pickman.realname}}</p>
          </template>
        </van-cell>
        <van-cell title="手机：">
          <template #default>
            <p class="cell-text right">{{pickman.phone}}</p>
          </template>
        </van-cell>
        <van-cell title="性别：">
          <template #default>
            <p class="cell-text right">{{pickman.sex}}</p>
          </template>
        </van-cell>
        <van-cell title="年龄：">
          <template #default>
            <p class="cell-text right">{{pickman.age}}</p>
          </template>
        </van-cell>
        <van-cell title="状态：">
          <template #default>
            <p class="cell-text right">{{pickman.status}}</p>
          </template>
        </van-cell>
      </van-cell-group>
    </div>
    <foot-bar></foot-bar>
  </div>
</template>

<script>
import { CellGroup, Cell } from 'vant'
import { mapGetters } from 'vuex'
import FootBar from '@/views/pick/components/FootBar'
export default {
  components: {
    VanCellGroup: CellGroup,
    VanCell: Cell,
    FootBar: FootBar
  },
  computed: {
    ...mapGetters(['username', 'avatar', 'pickman'])
  },
  mounted() {
    // console.log(this.pickman)
  }
}
</script>
<style scoped>
/deep/ div.van-cell__title {
  width: 90px;
  flex: none;
}
.cell-text {
  text-align: left;
  color: #323233;
}
.cell-text.right {
  text-align: right;
}
</style>

<style lang="scss" scoped>
.user {
  .userinfo {
    text-align: center;
    padding-top: 20px;
    .avatar {
      display: block;
      box-sizing: border-box;
      width: 80px;
      height: 80px;
      border: 2px solid #eee;
      border-radius: 50%;
      margin-left: auto;
      margin-right: auto;
      img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
      }
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
}
</style>
