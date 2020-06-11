<template>
  <div class="signin">
    <van-nav-bar
      title="登录"
      fixed
      placeholder
      border
    />
    <!-- left-text="返回"
    left-arrow
    @click-left="$router.go(-1)" -->
    <van-form class="form-wrap" @submit="onSubmit">
      <van-field
        v-model="formData.username"
        type="text"
        name="username"
        label="手机号"
        placeholder="输入手机号或用户名"
        :rules="[{ required: true, message: '请填写手机号或用户名' }]"
      />
      <van-field
        v-model="formData.password"
        type="password"
        name="password"
        label="密码"
        placeholder="输入密码"
        :rules="[{ required: true, message: '请填写密码' }]"
      />
      <div class="submit-wrap">
        <van-button round block type="info" native-type="submit">
          登录
        </van-button>
      </div>
    </van-form>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { NavBar, Field, Form, Button, Toast } from 'vant'
import api from '@/api/depot'
export default {
  components: {
    VanNavBar: NavBar,
    VanForm: Form,
    VanField: Field,
    VanButton: Button
  },
  data() {
    return {
      formData: {
        username: '',
        password: ''
      }
    }
  },
  computed: {
    ...mapGetters(['username'])
  },
  methods: {
    onSubmit() {
      api.signin(this.formData).then(response => {
        Toast(response.data.message || '登陆成功')
        this.$router.replace({ path: '/depot/user' })
      })
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
  // position: absolute;
  // bottom: 0;
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
