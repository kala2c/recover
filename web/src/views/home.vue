<template>
  <div class="home">
    <div class="list">
      <div class="link-wrap">
        <router-link to="/collect">我要回收物品</router-link>
      </div>
      <div class="link-wrap">
        <router-link to="/pick">我要当回收员</router-link>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.home {
  height: 100%;
  .list {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
    .link-wrap {
      padding: 30px 0;
    }
  }
}
</style>
