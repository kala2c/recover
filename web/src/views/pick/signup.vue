<template>
  <div class="signup">
    <van-nav-bar
      title="注册回收员"
      left-text="返回"
      left-arrow
      fixed
      placeholder
      border
      @click-left="$router.go(-1)"
    />
    <van-form class="form-wrap" @submit="onSubmit">
      <van-field
        v-model="formData.phone"
        type="tel"
        name="phone"
        label="手机号"
        placeholder="输入手机号"
        :rules="[{ required: true, message: '请填写手机号' }]"
      />
      <van-field
        v-if="false"
        v-model="formData.captcha"
        center
        clearable
        label="短信验证码"
        placeholder="请输入短信验证码"
      >
        <template #button>
          <van-button size="small" type="primary">发送验证码</van-button>
        </template>
      </van-field>
      <van-field
        v-model="formData.password"
        type="password"
        name="password"
        label="密码"
        placeholder="输入密码"
        :rules="[{ required: true, message: '请填写密码' }]"
      />
      <van-field
        v-model="formData.password2"
        type="password"
        name="password2"
        label="确认密码"
        placeholder="确认密码"
        :rules="[{ required: true, message: '请确认密码' }]"
      />
      <van-field
        v-model="formData.realname"
        name="realname"
        label="姓名"
        placeholder="输入姓名"
        :rules="[{ required: true, message: '请填写姓名' }]"
      />
      <div class="sex-radio form-ctrl">
          <div class="label">性别</div>
          <van-radio-group v-model="formData.sex" direction="horizontal">
            <van-radio name="男" checked-color="#07c160">男</van-radio>
            <van-radio name="女" checked-color="#07c160">女</van-radio>
          </van-radio-group>
        </div>
      <van-field
        v-model="formData.age"
        type="number"
        name="age"
        label="年龄"
        placeholder="输入年龄"
        :rules="[{ required: true, message: '请输入年龄' }]"
      />
      <div class="submit-wrap">
        <van-button round block type="info" native-type="submit">
          提交
        </van-button>
      </div>
    </van-form>
  </div>
</template>

<script>
import { NavBar, Field, Form, Button, RadioGroup, Radio } from 'vant'
export default {
  components: {
    VanNavBar: NavBar,
    VanForm: Form,
    VanField: Field,
    VanButton: Button,
    VanRadioGroup: RadioGroup,
    VanRadio: Radio
  },
  data() {
    return {
      formData: {
        phone: '',
        captcha: '',
        password: '',
        password2: '',
        age: '',
        realname: '',
        idnumber: ''
      }
    }
  },
  methods: {
    onSubmit() {
      console.log(this.formData)
    }
  }
}
</script>

<style lang="scss" scoped>
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
  background-color: #fff;
  .submit-btn {
    width: 95%;
    margin: 0 auto;
  }
}
</style>
