<template>
  <div class="picker-wrap">
    <van-popup v-model="pickerShow" position="bottom" :lazy-render='false'>
      <van-picker
        ref="picker"
        :title="title"
        show-toolbar
        class="picker"
        :columns="table"
        @confirm="onConfirm"
        @cancel="$emit('cancel')"
        />
    </van-popup>
  </div>
</template>

<script>
import api from '@/api/collect'
import { Picker, Popup } from 'vant'
export default {
  components: {
    VanPicker: Picker,
    VanPopup: Popup
  },
  props: {
    title: {
      type: String,
      default: '选择'
    },
    type: {
      type: String,
      default: 'time'
    },
    toggle: {
      type: Boolean,
      default: false
    },
    area: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      pickerShow: false,
      height: 100,
      areaTable: [],
      timeTable: []
    }
  },
  computed: {
    table() {
      let table = []
      if (this.type === 'time') table = this.timeTable
      if (this.type === 'area') table = this.areaTable
      return table
    }
  },
  watch: {
    toggle() {
      this.pickerShow = !this.pickerShow
    },
    area(val) {
      const area = val.split('-')
      // let id1 = null
      let id2 = null
      const idArr = []
      this.areaTable.forEach((district, index1) => {
        if (district.text === area[0]) {
          // id1 = district.id
          // id1 = index1
          idArr.push(index1)
          district.children.forEach((street, index2) => {
            if (street.text === area[1]) {
              id2 = street.id // 将根据街道id自动获取小区
              // id2 = index2
              idArr.push(index2)
            }
          })
        }
      })
      if (idArr.length > 1) {
        this.$refs.picker.setColumnIndex(0, idArr[0])
        this.$refs.picker.setColumnIndex(1, idArr[1])
        this.$emit('confirm', area, id2, true)
      }
    }
  },
  methods: {
    onConfirm(value, index) {
      if (this.type === 'area') {
        // const area = this.areaTable[index[0]].children[index[1]].children[index[2]]
        const area = this.areaTable[index[0]].children[index[1]]
        console.log(value)
        this.$emit('confirm', value, area.id)
        console.log(value)
      } else if (this.type === 'time') {
        this.$emit('confirm', value)
      }
    },
    init() {
      // if (this.type === 'time') {
      // 渲染时间菜单
      const dayTime = []
      for (let i = 8; i <= 17; i++) {
        dayTime.push(i + ':00')
      }
      const timeTable = [
        {
          values: ['明天', '后天'],
          defaultIndex: 0
        },
        {
          values: dayTime,
          defaultIndex: 0
        }
      ]
      this.timeTable = timeTable
      // } else
      if (this.type === 'area') {
        // 渲染地区菜单
        let areaTable = []
        api.getAreaTable().then(res => {
          const data = res.data
          areaTable = this.findChild(data)
          this.areaTable = areaTable
        })
      }
    },
    findChild(data) {
      if (data instanceof Array) {
        const rlt = data.map(item => {
          const obj = {}
          obj.id = item.id
          obj.text = item.name
          if (item.child) {
            obj.children = this.findChild(item.child)
          }
          return obj
        })
        return rlt
      }
    }
  },
  created() {
    this.init()
  }
}
</script>

<style lang="scss" scoped>
// .picker {
//   position: absolute;
//   z-index: 99;
//   width: 100%;
//   bottom: 0;
//   transition: all .6s ease;
// }
</style>
