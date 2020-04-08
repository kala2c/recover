<template>
  <div class="picker-wrap">
    <van-popup v-model="pickerShow" position="bottom">
      <van-picker
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
    }
  },
  methods: {
    onConfirm(value) {
      this.$emit('confirm', value)
    }
  },
  created() {
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
