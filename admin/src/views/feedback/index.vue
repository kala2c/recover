<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>

      <!-- 列表区域 -->
      <el-table :data="feedbackList" border stripe>
        <el-table-column type="index" />
        <el-table-column label="用户昵称">
          <template slot-scope="scope">
            {{ scope.row.user.username }}
          </template>
        </el-table-column>
        <el-table-column label="联系方式" prop="phone" />
        <el-table-column label="留言内容" prop="message" />
        <el-table-column label="留言时间" prop="create_time" />
        <el-table-column label="操作" width="200px">
          <template slot-scope="scope">
            <!-- 删除按钮 -->
            <el-tooltip effect="dark" content="删除" placement="top" :enterable="false">
              <el-button
                type="danger"
                icon="el-icon-delete"
                size="mini"
                @click="delFeedback(scope.row)"
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
import feedbackApi from '@/api/feedback'
export default {
  data() {
    return {
      // 获取用户列表的参数对象
      queryInfo: {
        // 当前的页数
        pagenum: 1,
        // 当前每页显示多少条数据
        pagesize: 10
      },
      feedbackList: [],
      total: 0
    }
  },
  created() {
    this.getFeedbackList()
  },
  methods: {
    // 获取用户列表
    async getFeedbackList() {
      const data = await feedbackApi.getFeedbackList(this.queryInfo)
      if (data.code !== 10000) {
        return this.$message.error('获取留言列表失败！')
      }
      this.feedbackList = data.data.list
      this.total = data.data.total
    },
    // 监听 pagesize 改变的事件
    handleSizeChange(newSize) {
      // console.log(newSize)
      this.queryInfo.pagesize = newSize
      this.getFeedbackList()
    },
    // 监听 页码值 改变的事件
    handleCurrentChange(newPage) {
      console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getFeedbackList()
    },
    delFeedback(user) {
      this.$confirm('是否要删除', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        feedbackApi.deleteFeedback({
          id: user.id
        }).then(response => {
          const data = response
          this.$message(data.message)
          this.getFeedbackList()
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
