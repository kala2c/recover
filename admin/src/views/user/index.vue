<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="5">
          <el-input placeholder="搜索微信昵称" v-model="queryInfo.username" clearable @clear="getUserList">
            <el-button slot="append" icon="el-icon-search" @click="getUserList"></el-button>
          </el-input>
        </el-col>
      </el-row>

      <!-- 用户列表区域 -->
      <el-table :data="userlist" border stripe>
        <el-table-column type="index"></el-table-column>
        <el-table-column label="微信昵称" prop="username"></el-table-column>
        <el-table-column label="openid" prop="openid" width="300px"></el-table-column>
        <el-table-column label="积分" prop="score"></el-table-column>
        <el-table-column label="状态" prop="status"></el-table-column>
        <el-table-column label="注册时间" prop="create_time">
<!--          <template slot-scope="scope">-->
<!--            <el-switch v-model="scope.row.mg_state" @change="userStateChanged(scope.row)">-->
<!--            </el-switch>-->
<!--          </template>-->
        </el-table-column>
        <el-table-column label="操作" width="80px">
          <template slot-scope>
           <!-- 删除按钮 -->
            <el-button type="danger" icon="el-icon-delete" size="mini"></el-button>
          </template>
        </el-table-column>
      </el-table>

      <!-- 分页区域 -->
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="queryInfo.pagenum" :page-sizes="[5, 10, 15, 20]" :page-size="queryInfo.pagesize" layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </el-card>
  </div>
</template>

<script>
import userApi from '@/api/user'
export default {
  data() {
    return {
      // 获取用户列表的参数对象
      queryInfo: {
        // 模糊查询
        username: '',
        // 当前的页数
        pagenum: 1,
        // 当前每页显示多少条数据
        pagesize: 10
      },
      userlist: [],
      total: 0
    }
  },
  created() {
    this.getUserList()
  },
  methods: {
    // 获取用户列表
    async getUserList() {
      const data = await userApi.getUserList(this.queryInfo)
      if (data.code !== 10000) {
        return this.$message.error('获取用户列表失败！')
      }
      this.userlist = data.data.userlist
      this.total = data.data.total
    },
    // 监听 pagesize 改变的事件
    handleSizeChange(newSize) {
      // console.log(newSize)
      this.queryInfo.pagesize = newSize
      this.getUserList()
    },
    // 监听 页码值 改变的事件
    handleCurrentChange(newPage) {
      console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getUserList()
    },
    // 监听添加用户对话框的关闭事件
    addDialogClosed() {
      this.$refs.addFormRef.resetFields()
    }
  }
}
</script>

<style lang="scss">
  .el-table{
    margin-top: 15px;
  }
</style>
