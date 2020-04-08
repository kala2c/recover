<template>
  <div class="address">
    <van-nav-bar
      title="新增地址"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
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
      <van-field
        label="详细地址"
        v-model="formData.detail"
        placeholder="填写街道-小区-门牌号"
      />
      <div class="submit-wrap">
        <van-button class="submit-btn" round block type="info" :disabled="submitDisabled" @click="onSubmit">
          提交
        </van-button>
      </div>
    </div>
    <picker
      ref="picker"
      :title="pickerTitle"
      :type="pickerType"
      :toggle="pickerShow"
      @confirm="onConfirm"
      @cancel="pickerShow = !pickerShow"
    />
  </div>
</template>

<script>
import store from '@/store'
import api from '@/api/collect'
import { NavBar, Toast, Field, Cell, Button } from 'vant'
import Picker from '@/views/collect/components/Picker'
export default {
  components: {
    VanNavBar: NavBar,
    VanField: Field,
    VanCell: Cell,
    VanButton: Button,
    Picker: Picker
  },
  data() {
    return {
      pickerTitle: '选择地区',
      pickerShow: false,
      pickerType: 'area',
      submitDisabled: false,
      formData: {
        id: null,
        phone: '',
        name: '',
        area: '未选择',
        detail: ''
      }
    }
  },
  methods: {
    openAreaPicker() {
      this.pickerTitle = '选择地区'
      this.pickerType = 'area'
      this.pickerShow = !this.pickerShow
    },
    onConfirm(value) {
      this.pickerShow = !this.pickerShow
      this.formData.area = value && value.join('-')
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
          this.$router.go(-1)
        }, 1300)
      }).catch(() => {
        this.submitDisabled = false
        store.dispatch('loading/close')
      })
    }
  },
  created() {
    const id = this.$route.query.id
    if (id) {
      store.dispatch('loading/open')
      api.getAddressById(id).then(response => {
        const data = response.data
        this.formData.id = data.id
        this.formData.name = data.name
        this.formData.phone = data.phone
        this.formData.area = data.area
        this.formData.detail = data.detail
        store.dispatch('loading/close')
      })
    }
  }
}
</script>

<style lang="scss" scoped>
/* 覆盖vant的样式 */
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
.address-form {
  padding: 20px 0;
}
.submit-wrap {
  padding: 5px 16px;
  .submit-button {
    width: 100%;
    height: 40px;
  }
}
</style>
