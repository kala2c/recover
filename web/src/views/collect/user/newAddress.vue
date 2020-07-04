<template>
  <div class="address">
    <van-nav-bar
      title="填写地址"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="goBack"
    />
    <div class="address-form">
      <van-field
        label="您的姓名"
        v-model="formData.name"
        placeholder="输入您的称呼"
      />
      <van-field
        label="联系方式"
        v-model="formData.phone"
        placeholder="输入您的电话或手机"
      />
      <van-cell title="选择地区" is-link @click="openAreaPicker">
        <template #default>
          <p class="cell-text">{{formData.area}}</p>
        </template>
      </van-cell>
      <van-cell title="选择小区" is-link @click="communityPickerShow = true">
        <template #default>
          <p class="cell-text">{{communityName || '未选择'}}</p>
        </template>
      </van-cell>
      <van-field
        label="详细地址"
        v-model="formData.detail"
        placeholder="填写到门牌号"
      />
      <div class="default-switch form-ctrl" v-if="isEdit">
        <div class="label">设为默认</div>
        <van-switch v-model="switchChecked" :disabled="switchDisabled" />
      </div>
      <div class="submit-wrap">
        <van-button class="submit-btn" round block type="primary" :disabled="submitDisabled" @click="onSubmit">
          提交
        </van-button>
      </div>
    </div>
    <van-popup v-model="communityPickerShow" position="bottom">
      <van-picker
        title="选择小区"
        show-toolbar
        class="picker"
        :columns="communityCols"
        @confirm="onChoseCommunity"
        @cancel="communityPickerShow = false"
        />
    </van-popup>
    <picker
      ref="picker"
      :title="pickerTitle"
      :type="pickerType"
      :toggle="pickerShow"
      :area="areaSelected"
      @confirm="onConfirm"
      @cancel="pickerShow = !pickerShow"
    />
  </div>
</template>

<script>
import store from '@/store'
import api from '@/api/collect'
import sysApi from '@/api/index'
import { NavBar, Picker, Toast, Field, Cell, Button, Switch, Popup } from 'vant'
import MyPicker from '@/views/collect/components/Picker'
export default {
  components: {
    VanNavBar: NavBar,
    VanField: Field,
    VanCell: Cell,
    VanButton: Button,
    VanSwitch: Switch,
    Picker: MyPicker,
    VanPicker: Picker,
    VanPopup: Popup
  },
  data() {
    return {
      pickerTitle: '选择地区',
      pickerShow: false,
      pickerType: 'area',
      communityPickerShow: false,
      switchChecked: true,
      switchDisabled: true,
      submitDisabled: false,
      communityList: [],
      communityCols: [],
      communityChosenIndex: 0,
      street: '',
      areaId: 0,
      areaSelected: '',
      isEdit: false,
      cbPath: '',
      formData: {
        id: null,
        phone: '',
        name: '',
        area: '未选择',
        area_id: '',
        detail: ''
      }
    }
  },
  computed: {
    communityName() {
      return this.communityList[this.communityChosenIndex] && this.communityList[this.communityChosenIndex].name
    },
    area() {
      if (this.communityName) {
        return this.street + '-' + this.communityName
      } else {
        return this.street
      }
    }
  },
  watch: {
    switchChecked(val) {
      if (val) {
        store.dispatch('loading/open')
        this.switchDisabled = true
        api.setDefaultAddress({ id: this.formData.id }).then(response => {
          this.switchChecked = true
          Toast(response.data.message || '设置成功')
          store.dispatch('loading/close')
        }).catch(() => {
          this.switchDisabled = false
          store.dispatch('loading/close')
        })
      }
    },
    areaId(val) {
      api.getCommunity({ street_id: val }).then(response => {
        const data = response.data
        this.communityList = []
        this.communityCols = []
        this.communityList = data
        this.communityList.forEach(item => {
          this.communityCols.push(item.name)
        })
      })
    },
    area(val) {
      this.formData.area = val
    }
  },
  methods: {
    goBack() {
      this.$router.replace({ path: this.cbPath || '/collect/user/address' })
    },
    openAreaPicker() {
      this.pickerTitle = '选择街道'
      this.pickerType = 'area'
      this.pickerShow = !this.pickerShow
    },
    onConfirm(value, areaId, isShow) {
      // 判断是不是自动填入 自动填入时不需要改变展示状态
      if (!isShow) {
        this.pickerShow = !this.pickerShow
      }
      this.street = value && value.join('-')
      this.areaId = areaId || 0
    },
    onChoseCommunity(value, communityIndex) {
      // console.log(value, communityIndex)
      this.communityChosenIndex = communityIndex
      this.formData.area_id = this.communityList[communityIndex].id || 0
      this.communityPickerShow = false
    },
    // 获取当前位置
    async getLocation() {
      const that = this
      const wx = window.wx
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
              const selfLocation = res.latitude + ',' + res.longitude
              api.getStreet({
                location: selfLocation
              }).then(response => {
                const data = response.data
                const areaInfo = data.regeocode && data.regeocode.addressComponent
                if (!areaInfo) {
                  Toast('自动获取位置失败')
                } else {
                  const { city, district, township } = areaInfo
                  // const { city, district, township, streetNumber } = areaInfo
                  console.log(city, district, township)
                  // const detail = streetNumber.street + streetNumber.number
                  // this.formData.area = district + '-' + township
                  that.areaSelected = district + '-' + township
                  console.log(that.areaSelected)
                  // that.formData.detail = detail || ''
                }
              })
            }
          })
        })
      }).catch(res => {
        // console.log(res)
      })
    },
    onSubmit() {
      if (!this.formData.name) {
        Toast('请输入称呼')
        return false
      }
      if (!this.formData.phone) {
        Toast('请输入联系方式')
        return false
      }
      if (!this.formData.area || this.formData.area === '未选择1') {
        Toast('请选择地区')
        return false
      }
      if (!this.formData.detail) {
        Toast('请输入详细地址')
        return false
      }
      const data = {
        name: this.formData.name,
        phone: this.formData.phone,
        area: this.formData.area,
        area_id: this.formData.area_id,
        detail: this.formData.detail
      }
      if (this.formData.id) {
        data.id = this.formData.id
      }
      this.submitDisabled = true
      store.dispatch('loading/open')
      api.saveAddress(data).then(response => {
        store.dispatch('loading/close')
        Toast(response.message)
        setTimeout(() => {
          this.submitDisabled = false
          this.goBack()
        }, 1300)
      }).catch(() => {
        this.submitDisabled = false
        store.dispatch('loading/close')
      })
    }
  },
  created() {
    const id = this.$route.query.id
    const cbPath = this.$route.query && this.$route.query.cbPath
    this.cbPath = cbPath
    // 编辑时
    if (id) {
      store.dispatch('loading/open')
      api.getAddressById(id).then(response => {
        const data = response.data
        this.formData.id = data.id
        this.formData.name = data.name
        this.formData.phone = data.phone
        const area = data.area.split('-')
        area.pop()
        this.formData.area = area.join('-')
        console.log(data.area.split('-').pop())
        this.formData.area_id = data.area_id
        this.formData.detail = data.detail
        this.isEdit = true
        if (data.status !== 1) {
          this.switchChecked = false
          this.switchDisabled = false
        }
        store.dispatch('loading/close')
      })
    } else {
      this.getLocation()
    }
  }
}
</script>

<style scoped>
/* 覆盖vant的样式 */
/deep/ div.van-cell__title {
  width: 90px;
  flex: none;
}
.cell-text {
  text-align: left;
  color: #323233;
}
</style>

<style lang="scss" scoped>
.address-form {
  padding: 20px 0;
}
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
.submit-wrap {
  padding: 5px 16px;
  .submit-button {
    width: 100%;
    height: 40px;
  }
}
</style>
