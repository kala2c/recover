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
        <van-button class="submit-btn" round block type="info" native-type="submit">
          提交
        </van-button>
      </div>
    </van-form>
  </div>
</template>

<script>
import { Form, Field, Button } from 'vant'
export default {
  components: {
    VanForm: Form,
    VanField: Field,
    VanButton: Button
  },
  data() {
    return {
      phone: '',
      message: ''
    }
  },
  methods: {
    onSubmit() {
      console.log('submit!')
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
