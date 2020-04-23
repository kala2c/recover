<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="5">
          <el-input v-model="queryInfo.username" placeholder="搜索用户姓名" clearable @clear="getOrderList">
            <el-button slot="append" icon="el-icon-search" @click="getOrderList" />
          </el-input>
        </el-col>
        <el-col :span="5">
          <el-input v-model="queryInfo.phone" placeholder="搜索手机号" clearable @clear="getOrderList">
            <el-button slot="append" icon="el-icon-search" @click="getOrderList" />
          </el-input>
        </el-col>
        <el-col :span="5">
          <el-input v-model="queryInfo.orderno" placeholder="搜索订单号" clearable @clear="getOrderList">
            <el-button slot="append" icon="el-icon-search" @click="getOrderList" />
          </el-input>
        </el-col>
      </el-row>

      <!-- 用户列表区域 -->
      <el-table :data="orderlist" border stripe>
        <el-table-column type="index" />
        <el-table-column label="订单编号" prop="order_no" />
        <el-table-column label="姓名" prop="username" />
        <el-table-column label="电话" prop="phone" />
        <el-table-column label="下单时间" prop="create_time" />
        <el-table-column label="地区" prop="area" />
        <el-table-column label="详细地址" prop="address_detail" />
        <el-table-column label="废品" prop="waste.name" />
        <el-table-column label="数量" prop="waste_number" />
        <el-table-column label="单位" prop="waste.unit" />
        <el-table-column label="状态" prop="status_text" />
        <el-table-column label="操作" width="80px">
          <template slot-scope="scope">
            <!-- 删除按钮 -->
            <el-tooltip effect="dark" content="取消订单" placement="top" :enterable="false">
              <el-button type="danger" icon="el-icon-delete" size="mini" @click="deletebox(scope.row.id)" />
            </el-tooltip>
            <el-tooltip effect="dark" content="标记完成" placement="top" :enterable="false">
              <el-button type="primary" icon="el-icon-success" size="mini" @click="complete(scope.row.id)" />
            </el-tooltip>
          </template>
        </el-table-column>
      </el-table>

      <!-- 分页区域 -->
      <el-pagination :current-page="queryInfo.pagenum" :page-sizes="[5, 10, 15, 20]" :page-size="queryInfo.pagesize" layout="total, sizes, prev, pager, next, jumper" :total="total" @size-change="handleSizeChange" @current-change="handleCurrentChange" />
    </el-card>
  </div>
</template>

<script>
import orderApi from '@/api/order'
export default {
  data() {
    return {
      // 获取订单列表的参数对象
      queryInfo: {
        // 模糊查询
        username: '',
        orderno: '',
        phone: '',
        // 当前的页数
        pagenum: 1,
        // 当前每页显示多少条数据
        pagesize: 10
      },
      orderlist: [],
      status: null,
      total: 0
    }
  },
  computed: {
    statusMsg(status) {
      return (status) => {
        this.status[status]
      }
    }
  },
  created() {
    this.getOrderList()
  },
  methods: {
    // 获取用户列表
    async getOrderList() {
      const data = await orderApi.getOrderList(this.queryInfo)
      if (data.code !== 10000) {
        return this.$message.error('获取订单列表失败！')
      }
      this.orderlist = data.data.orderlist
      this.status = data.data.status
      this.total = data.data.total
    },
    // 监听 pagesize 改变的事件
    handleSizeChange(newSize) {
      // console.log(newSize)
      this.queryInfo.pagesize = newSize
      this.getOrderList()
    },
    // 监听 页码值 改变的事件
    handleCurrentChange(newPage) {
      console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getOrderList()
    },
    // 监听添加用户对话框的关闭事件
    addDialogClosed() {
      this.$refs.addFormRef.resetFields()
    },
    deletebox(id) {
      this.$confirm('是否要取消此订单?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.deleteOrder(id)
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '取消操作'
        })
      })
    },
    async deleteOrder(id) {
      const data = await orderApi.deleteOrder({ 'id': id })
      if (data.code !== 10000) {
        return this.$message.error('取消订单失败！')
      }
      this.$message({
        type: 'success',
        message: '订单取消成功!'
      })
      this.getOrderList()
    },
    complete(id) {
      this.$confirm('是否确认订单完成?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        orderApi.completeOrder({ id: id }).then(response => {
          this.$message({
            type: 'success',
            message: response.data.message || '取消成功'
          })
          this.getOrderList()
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消取消订单'
        })
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
