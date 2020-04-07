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
    <div class="type">
      <van-cell title="选择回收类型"></van-cell>
      <van-grid square>
        <van-grid-item
          v-for="value in 6"
          :key="value"
          icon="photo-o"
          text="文字"
          class="type-grid-item"
          :class="{ 'type-active': value === formData.type }"
          @click="formData.type = value"
        />
      </van-grid>
    </div>
    <div class="price">
      <p>今日指导价：{{lowPrice}}-{{highPrice}}元/公斤<router-link class="link" to="/collect/query/price">详细</router-link></p>
    </div>
    <!-- <div class="weight">
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
    </div> -->
    <div class="form-wrap">
      <div class="time">
        <div class="time-radio form-ctrl">
          <div class="label">取货时间</div>
          <van-radio-group v-model="formData.pickFast" direction="horizontal">
            <van-radio name="1" checked-color="#07c160">尽快上门</van-radio>
            <van-radio name="2" checked-color="#07c160">预约时间</van-radio>
          </van-radio-group>
        </div>
        <van-cell
          v-if="formData.pickFast === '2'" title="选择时间"
          @click="openTimePicker"
          is-link
        >
          <template #default>
            <p class="cell-text">{{formData.pickTime}}</p>
          </template>
        </van-cell>
      </div>
      <van-cell title="选择地址" is-link>
        <template #default>
          <p class="cell-text">{{formData.address}}</p>
        </template>
      </van-cell>
      <van-field label="预估重量"></van-field>
      <van-field label="备注"></van-field>
      <div class="submit-wrap">
        <van-button class="submit-btn" round block type="info" native-type="submit">
          一键预约
        </van-button>
      </div>
    </div>
    <picker
      ref="picker"
      :title="pickerTitle"
      :type="pickerType"
      :open="pickerShow"
      @confirm="onConfirm"
      @cancel="pickerShow = !pickerShow"
    />
  </div>
</template>

<script>
import { NavBar, Button, Field, Cell, RadioGroup, Radio, Grid, GridItem } from 'vant'
import Picker from './components/Picker'
export default {
  components: {
    VanNavBar: NavBar,
    VanField: Field,
    VanButton: Button,
    VanCell: Cell,
    VanRadioGroup: RadioGroup,
    VanRadio: Radio,
    VanGrid: Grid,
    VanGridItem: GridItem,
    Picker
  },
  data() {
    return {
      lowPrice: 1.00,
      highPrice: 2.00,
      pickerTitle: '',
      pickerType: '',
      pickerShow: false,
      formData: {
        pickFast: '1',
        pickTime: '',
        type: 1
      }
    }
  },
  methods: {
    openTimePicker() {
      this.pickerTitle = '选择时间'
      this.pickerType = 'time'
      this.pickerShow = !this.pickerShow
    },
    onConfirm(value) {
      this.pickerShow = !this.pickerShow
      this.formData.pickTime = value.join('-')
    }
  },
  created() {
  },
  mounted() {
    setInterval(() => {
      // console.log(this.formData)
    }, 1000)
  }
}
</script>

<style scoped>
/*覆盖vant样式*/
div.van-cell__title {
  width: 90px!important;
  flex: none;
}
.cell-text {
  text-align: left;
  color: #323233;
}
</style>

<style lang="scss" scoped>
.type {
  margin-top: 15px;
}
.type-grid-item {
  border: 1px solid #ebedef;
}
.type-active {
  box-sizing: border-box;
  color: #07c160;
  border: 1px solid #07c160;
}
.price {
  width: 100%;
  padding: 10px 16px;
  margin-top: 15px;
  // box-shadow: 0 2px 2px 0 #eee;
  background-color: #fff;
  line-height: 1.5;
  .link {
    margin-left: 15px;
    color: #1e9fff;
  }
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
.form-wrap {
  margin-top: 15px;
  .form-ctrl {
    display: flex;
    padding: 10px 16px;
    color: #323233;
    font-size: 14px;
    line-height: 24px;
    background-color: #fff;
    border-bottom: 1px solid #efffff;
    .label {
      width: 90px;
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
