<template>
  <div class="address">
    <van-address-list
      v-model="chosenAddressId"
      :list="list"
      default-tag-text="默认"
      @add="onAdd"
      @edit="onEdit"
    />
    <!-- :disabled-list="disabledList"
    disabled-text="以下地址超出配送范围" -->
  </div>
</template>

<script>
import { AddressList, Toast } from 'vant'
export default {
  components: {
    VanAddressList: AddressList
  },
  data() {
    return {
      chosenAddressId: '1',
      list: [
        {
          id: '1',
          name: '陈禄伟',
          tel: '17865538888',
          address: '山东省烟台市芝罘区红旗中路186号'
        },
        {
          id: '2',
          name: '小小小',
          tel: '15612340000',
          address: '山东省烟台市芝罘区'
        }
      ],
      disabledList: [
        {
          id: '3',
          name: '王五',
          tel: '1320000000',
          address: '浙江省杭州市滨江区江南大道 15 号'
        }
      ]
    }
  },
  methods: {
    onAdd() {
      Toast('新增地址')
    },
    onEdit(item, index) {
      Toast('编辑地址:' + index)
    }
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

<style lang="scss" scoped>

</style>
