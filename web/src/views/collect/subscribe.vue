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
      <van-cell title="选择物品类型" is-link @click="wastePickerShow = true">
        <template #default>
          <p class="cell-text right">{{wasteName}}</p>
        </template>
      </van-cell>
      <van-grid v-if="false" square>
        <van-grid-item
          v-for="(waste, index) in wasteList"
          :key="waste.id"
          icon="photo-o"
          :text="waste.name"
          class="type-grid-item"
          :class="{ 'type-active': waste.id === orderForm.waste_id }"
          @click="onChangeWaste(waste, index)"
        />
      </van-grid>
    </div>
    <div class="price">
      <p>
        <!-- 今日指导价：{{lowPrice}}-{{highPrice}}元/ -->
        今日指导价：{{price}}/{{unit}}
        <router-link class="link" to="/collect/query/price">详细</router-link>
      </p>
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
          <van-radio-group v-model="formWatcher.pick_fast" direction="horizontal">
            <van-radio name="1" checked-color="#07c160">尽快上门</van-radio>
            <van-radio name="2" checked-color="#07c160">预约时间</van-radio>
          </van-radio-group>
        </div>
        <van-cell
          v-if="orderForm.pick_fast === '2'" title="选择时间"
          @click="openTimePicker"
          is-link
        >
          <template #default>
            <p class="cell-text">{{orderForm.pick_time || '未选择'}}</p>
          </template>
        </van-cell>
      </div>
      <van-cell title="选择联系方式及地址" @click="onChooseAddress" is-link>
        <template #default>
          <p class="cell-text">{{orderForm.username || '未选择'}} {{orderForm.phone}}</p>
          <p class="cell-text">{{orderForm.area}}{{orderForm.address_detail}}</p>
        </template>
      </van-cell>
      <van-field label="预估数量" v-model="formWatcher.waste_number" placeholder="瓶子等填写个数 其它填写重量"></van-field>
      <van-field
        label="备注"
        type="textarea"
        autosize
        v-model="formWatcher.note"
        placeholder="填写备注信息"
      />
      <div class="submit-wrap">
        <van-button class="submit-btn" round block type="primary" :disabled="submitDisabled" @click="onSubmit">
          一键预约
        </van-button>
      </div>
    </div>
    <van-popup v-model="wastePickerShow" position="bottom">
      <van-picker
        show-toolbar
        :columns="wasteColumns"
        @cancel="wastePickerShow = false"
        @confirm="onWastePickerConfirm"
      />
    </van-popup>
    <picker
      ref="picker"
      :title="timePickerTitle"
      :type="timePickerType"
      :toggle="timePickerShow"
      @confirm="onTimePickerConfirm"
      @cancel="timePickerShow = !timePickerShow"
    />
  </div>
</template>

<script>
import store from '@/store'
import { mapGetters } from 'vuex'
import api from '@/api/collect'
import { NavBar, Button, Field, Cell, RadioGroup, Radio, Grid, GridItem, Popup, Picker, Toast } from 'vant'
import CPicker from './components/Picker'
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
    VanPopup: Popup,
    VanPicker: Picker,
    Picker: CPicker
  },
  data() {
    return {
      lowPrice: 1.00,
      highPrice: 2.00,
      timePickerTitle: '',
      timePickerType: '',
      timePickerShow: false,
      wastePickerShow: false,
      wasteColumns: [],
      wasteList: [],
      submitDisabled: false,
      formWatcher: {
        waste_id: '',
        pick_time: '',
        pick_fast: '',
        address: '',
        waste_number: '',
        note: ''
      }
    }
  },
  watch: {
    formWatcher: {
      handler(val) {
        // for (const item in newVal) {
        //   if (newVal[item] !== oldVal[item]) {
        //     store.dispatch('orderForm/setData', newVal)
        //   }
        // }
        store.dispatch('orderForm/setData', val)
      },
      deep: true
    }
  },
  computed: {
    ...mapGetters(['wasteIndex', 'orderForm']),
    wasteChosen() {
      return this.wasteList[this.wasteIndex]
    },
    wasteName() {
      return this.wasteChosen && this.wasteChosen.name
    },
    price() {
      return this.wasteChosen && this.wasteChosen.price
    },
    unit() {
      return this.wasteChosen && this.wasteChosen.unit
    }
  },
  methods: {
    openTimePicker() {
      this.timePickerTitle = '选择时间'
      this.timePickerType = 'time'
      this.timePickerShow = !this.timePickerShow
    },
    onTimePickerConfirm(value) {
      this.timePickerShow = !this.timePickerShow
      this.formWatcher.pick_time = value.join('-')
    },
    onWastePickerConfirm(value, index) {
      this.onChangeWaste(this.wasteList[index], index)
      this.wastePickerShow = false
    },
    onChangeWaste(waste, index) {
      this.formWatcher.waste_id = waste.id
      store.dispatch('orderForm/setWasteIndex', index)
    },
    onChooseAddress() {
      this.$router.replace({ path: '/collect/user/address?chosen=1&cbPath=' + this.$route.path })
    },
    onSubmit() {
      store.dispatch('loading/open')
      this.submitDisabled = true
      const formData = {}
      Object.assign(formData, this.orderForm)
      formData.pick_time = this.encodeTime(formData.pick_time)
      console.log(formData)
      api.submitOrder(formData).then(response => {
        Toast(response.data.message || '下单成功')
        store.dispatch('loading/close')
        setTimeout(() => {
          store.dispatch('orderForm/clear')
          this.$router.push({ path: '/collect/order' })
        }, 1000)
      }).catch(() => {
        this.submitDisabled = false
        store.dispatch('loading/close')
      })
    },
    encodeTime(time) {
      const now = new Date()
      let timestamp = now.getTime()
      const timeInfo = time.split('-')
      if (timeInfo[0] === '明天') {
        timestamp += 86400000
      } else if (timeInfo[0] === '后天') {
        timestamp += 86400000 * 2
      }
      const temp = new Date(timestamp)
      const pickDate = temp.getFullYear() + '-' + (temp.getMonth() + 1) + '-' + temp.getDate() + ' ' + timeInfo[1]
      const pickTime = (new Date(pickDate)).getTime() + ''
      return pickTime.substr(0, 10)
    }
  },
  created() {
    this.formWatcher = this.orderForm
    store.dispatch('loading/open')
    api.getOrderInfo().then(response => {
      const data = response.data
      this.wasteList = data.waste_list
      this.wasteList.forEach(item => {
        this.wasteColumns.push(item.name)
      })
      if (!this.orderForm.waste_id && this.wasteList.length > 0) {
        this.formWatcher.waste_id = this.wasteList[0].id
        store.dispatch('orderForm/setWasteIndex', 0)
      }
      if (!this.orderForm.username && data.default_address) {
        store.dispatch('orderForm/setAddress', data.default_address)
      }
      store.dispatch('loading/close')
    }).catch(() => {
      store.dispatch('loading/close')
    })
  }
}
</script>

<style scoped>
/*覆盖vant样式*/
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
.subscribe {
  padding-bottom: 65px;
}
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
  padding: 10px 16px;
  margin-top: 15px;
  // box-shadow: 0 2px 2px 0 #eee;
  // background-color: #fff;
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
    // background-color: #fff;
    border-bottom: 1px solid #efffff;
    .label {
      width: 90px;
    }
  }
}
.submit-wrap {
  // position: absolute;
  // bottom: 0;
  width: 100%;
  padding: 10px 0;
  margin-top: 16px;
  // background-color: #fff;
  .submit-btn {
    width: 95%;
    margin: 0 auto;
  }
}
</style>
