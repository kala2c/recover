<template>
  <div class="feedback">
    <van-form class="form" @submit="onSubmit">
      <van-field
        v-model="phone"
        name="联系方式"
        label="联系方式"
        placeholder="请输入您的联系方式"
        :rules="[{ required: true, message: '请填写联系方式' }]"
      />
      <van-field
        v-model="message"
        rows="5"
        autosize
        label="内容"
        type="textarea"
        placeholder="请输入您的问题或意见"
      />
      <div class="submit-wrap">
        <van-button class="submit-btn" :disabled="disableButton" round block type="info" native-type="submit">
          提交
        </van-button>
      </div>
    </van-form>
  </div>
</template>

<script>
import { Form, Field, Button, Toast } from 'vant'
import collectApi from '@/api/collect'
export default {
  components: {
    VanForm: Form,
    VanField: Field,
    VanButton: Button
  },
  data() {
    return {
      phone: '',
      message: '',
      disableButton: false
    }
  },
  methods: {
    onSubmit() {
      const that = this
      that.disableButton = true
      collectApi.submitFeedback({
        phone: this.phone,
        message: this.message
      }).then(response => {
        Toast(response.message)
        setTimeout(function() {
          that.$router.push('/collect/user')
          this.disableButton = false
        }, 1500)
      }).catch(() => {
        this.disableButton = false
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.form {
  height: 100%;
  padding-top: 15px;
  padding-bottom: 50px;
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
