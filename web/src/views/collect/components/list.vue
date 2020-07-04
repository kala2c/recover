<template>
  <div class="group">
    <div
      class="group-item"
      :style="'padding-bottom:'+ (item.level-1)*5 +'px'"
      v-for="item in tree"
      :key="item.id"
    >
      <p class="group-item-name">
        {{ item.name }}
      </p>
      <list
        v-if="item.child"
        :tree="item.child"
      ></list>
    </div>
  </div>
</template>

<script>
import List from '@/views/collect/components/list'
export default {
  name: 'list',
  components: {
    List
  },
  props: {
    tree: {
      type: Array
    }
  },
  mounted() {
    console.log(this.tree)
  }
}
</script>

<style scoped>
.group {
  padding-left: 15px;
}
</style>
