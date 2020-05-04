<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="5">
          <el-input v-model="queryInfo.username" placeholder="搜索微信昵称" clearable @clear="getUserList">
            <el-button slot="append" icon="el-icon-search" @click="getUserList" />
          </el-input>
        </el-col>
      </el-row>

      <!-- 用户列表区域 -->
      <el-table :data="userlist" border stripe>
        <el-table-column type="index" />
        <el-table-column label="微信openid" prop="openid" width="300px" />
        <el-table-column label="用户id" prop="id" />
        <el-table-column label="微信昵称" prop="username" />
        <el-table-column label="积分" prop="score" />
        <el-table-column label="用户地址">
          <template slot-scope="scope">
            <el-popover
              placement="top-start"
              width="300"
              trigger="click"
            >
              <div v-if="scope.row.address.length > 0" class="address-list-group">
                <div
                  v-for="address in scope.row.address"
                  :key="address.id"
                  class="address-list-group-item"
                >
                  <p>
                    <span>{{ address.name }} {{ address.phone }}</span>
                    <br>
                    <span>{{ address.area }} {{ address.detail }}</span>
                  </p>
                </div>
              </div>
              <div v-else>该用户暂没有编辑地址</div>
              <el-button slot="reference">查看</el-button>
            </el-popover>
          </template>
        </el-table-column>
        <el-table-column label="注册时间" prop="create_time" />
        <el-table-column label="用户状态" prop="statusText" />
        <el-table-column label="操作" width="200px">
          <template slot-scope="scope">
            <!-- 拉黑按钮 -->
            <el-tooltip effect="dark" content="封禁用户" placement="top" :enterable="false">
              <el-button
                v-if="scope.row.status === 0"
                type="danger"
                icon="el-icon-circle-close"
                size="mini"
                @click="lockUser(scope.row)"
              />
            </el-tooltip>
            <!-- 还原按钮 -->
            <el-tooltip effect="dark" content="解除封禁" placement="top" :enterable="false">
              <el-button
                v-if="scope.row.status === 1"
                type="primary"
                icon="el-icon-success"
                size="mini"
                @click="unlockUser(scope.row)"
              />
            </el-tooltip>
          </template>
        </el-table-column>
      </el-table>

      <!-- 分页区域 -->
      <el-pagination
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
        :page-size="queryInfo.pagesize"
        :page-sizes="[5, 10, 15, 20]"
        :current-page="queryInfo.pagenum"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
      />
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
    },
    lockUser(user) {
      this.$confirm('是否要封禁用户', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        userApi.lock({ user_id: user.id }).then(response => {
          this.$message({
            type: 'success',
            message: response.data.message || '禁用成功'
          })
          this.getUserList()
        })
      }).catch(() => {
      })
    },
    unlockUser(user) {
      this.$confirm('是否要解除封禁', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        userApi.unlock({ user_id: user.id }).then(response => {
          this.$message({
            type: 'success',
            message: response.data.message || '解除成功'
          })
          this.getUserList()
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
</style>
