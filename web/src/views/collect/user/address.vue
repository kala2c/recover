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
      v-model="addressId"
      :list="addressShowList"
      default-tag-text="默认"
      :add-button-text="addButtonText"
      :switchable="isChosen"
      @add="onConfirm"
      @edit="onEdit"
      @select="onSelect"
      @check-item="onCheck"
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
      addressId: '0',
      chosenAddressIndex: 0,
      chosenAddressItem: null,
      // 地址原信息
      addressInfoList: [],
      // 用于展示的列表信息
      addressShowList: [
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
        const address = this.addressInfoList[this.chosenAddressIndex]
        store.dispatch('orderForm/setAddress', address)
        this.goBack()
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
    toEditPage(id) {
      const query = {
        cbPath: this.$route.fullPath
      }
      if (id) {
        query.id = id
      }
      this.$router.replace({ path: '/collect/user/new/address', query })
    },
    onAdd() {
      this.toEditPage()
    },
    onEdit(item, index) {
      this.toEditPage(item.id)
      // this.$router.replace({ path: '/collect/user/new/address?id=' + item.id })
    },
    onCheck(item, index) {
      console.log(item)
    },
    onSelect(item, index) {
      this.chosenAddressIndex = index
      this.chosenAddressItem = item
    }
  },
  created() {
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
      this.addressInfoList = data
      data.forEach((item, index) => {
        this.addressShowList.push({
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
