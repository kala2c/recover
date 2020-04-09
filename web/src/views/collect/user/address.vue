<template>
  <div class="address">
    <van-nav-bar
      :title="title"
      left-text="返回"
      left-arrow
      right-text="新增"
      fixed
      placeholder
      border
      @click-left="goBack()"
      @click-right="onAdd"
    />
    <van-address-list
      v-model="chosenAddressIndex"
      :list="addressList"
      default-tag-text="默认"
      :add-button-text="addButtonText"
      :switchable="isChosen"
      @add="onConfirm"
      @edit="onEdit"
      @select="onSelect"
    />
    <!-- :disabled-list="disabledList"
    disabled-text="以下地址超出配送范围" -->
  </div>
</template>

<script>
import api from '@/api/collect'
import store from '@/store'
import { NavBar, AddressList } from 'vant'
export default {
  components: {
    VanNavBar: NavBar,
    VanAddressList: AddressList
  },
  data() {
    return {
      title: '管理地址',
      isChosen: false,
      cbPath: '',
      addButtonText: '新增地址',
      chosenAddressIndex: '0',
      chosenAddressItem: null,
      addressList: [
        // {
        //   id: '1',
        //   name: '陈禄伟',
        //   tel: '17865538888',
        //   address: '山东省烟台市芝罘区红旗中路186号',
        //   isDefault: true
        // }
      ]
    }
  },
  methods: {
    onConfirm() {
      if (this.isChosen) {
        // const address = this.addressList[this.chosenAddressIndex].address
        const address = this.chosenAddressItem.address
        store.dispatch('orderForm/setData', { address })
        this.$router.replace({ path: this.cbPath })
      } else {
        this.onAdd()
      }
    },
    goBack() {
      if (this.isChosen) {
        this.$router.replace({ path: this.cbPath })
      } else {
        this.$router.go(-1)
      }
    },
    onAdd() {
      this.$router.replace({ path: '/collect/user/new/address' })
    },
    onEdit(item, index) {
      this.$router.replace({ path: '/collect/user/new/address?id=' + item.id })
    },
    onSelect(item, index) {
      console.log(item, index)
      this.chosenAddressItem = item
    }
  },
  created() {
    console.log(this.$route)
    const isChosen = this.$route.query && this.$route.query.chosen
    const cbPath = this.$route.query && this.$route.query.cbPath
    if (isChosen && parseInt(isChosen) === 1) {
      this.isChosen = true
      this.cbPath = cbPath
      this.title = '选择地址'
      this.addButtonText = '确定'
    }
    store.dispatch('loading/open')
    api.getAddressList().then(response => {
      store.dispatch('loading/close')
      const data = response.data
      data.forEach((item, index) => {
        this.addressList.push({
          id: item.id,
          name: item.name,
          tel: item.phone,
          address: item.area.split('-').join('') + item.detail,
          isDefault: item.status === 1
        })
      })
    }).catch(() => {
      store.dispatch('loading/close')
    })
  }
}
</script>

<style lang="scss" scoped>
/* 覆盖vant的样式 */
.van-address-item .van-icon-success {
  background-color: #59c261!important;
  border-color: #59c261!important;
}
// .van-address-item .van-radio__icon--checked .van-icon {
//   background-color: #59c261;
//   border-color: #59c261;
// }
.van-button--danger {
  color: #fff!important;
  background-color: #1989fa!important;
  border: 1px solid #1989fa!important;
}
</style>
