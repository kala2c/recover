<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="4">
          <el-upload
            :action="uploadUrl"
            list-type="text"
            :multiple="false"
            :headers="header"
            :on-success="onSuccess"
          >
            <el-button type="primary">添加轮播图</el-button>
          </el-upload>
        </el-col>
      </el-row>

      <!-- 列表区域 -->
      <el-table :data="list" border stripe>
        <el-table-column type="index" width="100px" />
        <el-table-column label="图" width="320px">
          <template slot-scope="scope">
            <img :src="scope.row.url" class="banner-img" alt="">
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200px">
          <template slot-scope="scope">
            <el-button type="danger" icon="el-icon-delete" size="mini" @click="onDelete(scope.row)" />
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
import bannerApi from '@/api/banner'
import { getToken } from '@/utils/auth'
export default {
  data() {
    return {
      list: [],
      uploadUrl: bannerApi.uploadBannerUrl,
      header: { Authorization: 'bearer ' + getToken() }
    }
  },
  created() {
    this.getBannerList()
  },
  methods: {
    async getBannerList() {
      const res = await bannerApi.getBannerList()
      console.log(res)
      if (res.code !== 10000) {
        return this.$message.error('获取列表失败')
      }
      this.list = res.data
    },
    onSuccess() {
      this.getBannerList()
    },
    onDelete(banner) {
      this.$confirm('是否要删除', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        bannerApi.delBanner({ id: banner.id }).then(response => {
          this.$message.success(response.message || '删除成功')
          this.getBannerList()
        })
      }).catch(() => {
      })
    }
  }
}
</script>

<style lang="scss">
.el-table{
    margin-top: 15px;
}
.banner-img {
  width: 280px;
}
</style>
