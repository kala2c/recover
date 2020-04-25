<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="5">
          <el-input
            v-model="queryInfo.realname"
            placeholder="搜索真实姓名"
            clearable
            @change="getPickmanList"
            @clear="getPickmanList"
          >
            <el-button slot="append" icon="el-icon-search" @click="getPickmanList" />
          </el-input>
        </el-col>
        <el-col :span="5">
          <el-input
            v-model="queryInfo.phone"
            placeholder="搜索手机号"
            clearable
            @change="getPickmanList"
            @clear="getPickmanList"
          >
            <el-button slot="append" icon="el-icon-search" @click="getPickmanList" />
          </el-input>
        </el-col>
        <el-col :span="5">
          <el-select v-model="queryInfo.status" placeholder="取货员状态" @change="getPickmanList">
            <el-option
              v-for="item in pickmanstatus"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-col>
      </el-row>

      <!-- 用户列表区域 -->
      <el-table :data="userlist" border stripe>
        <el-table-column type="index" />
        <el-table-column label="真实姓名" prop="realname" />
        <el-table-column label="openid" prop="openid" width="300px" />
        <el-table-column label="手机" prop="phone" />
        <el-table-column label="性别" prop="sex" />
        <el-table-column label="年龄" prop="age" />
        <el-table-column label="注册时间">
          <template slot-scope="scope">
            <!--只显示日期，不显示具体的时间-->
            <p>{{ scope.row.create_time.split(' ')[0] }}</p>
          </template>
        </el-table-column>
        <el-table-column label="状态">
          <!--取货员账号的四种状态和对应的操作-->
          <template slot-scope="scope">
            <el-popover v-if="scope.row.status == 0">
              <el-button type="success" @click="PickmanStatus(scope.row.id, 1)">通过审核</el-button>
              <div slot="reference" class="name-wrapper">
                <el-tag size="info">待审核</el-tag>
              </div>
            </el-popover>
            <el-popover v-else-if="scope.row.status == 1" trigger="hover" placement="left">
              <el-button type="danger" @click="PickmanStatus(scope.row.id, -1)">冻结账号</el-button>
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">正常</el-tag>
              </div>
            </el-popover>
            <!-- <el-popover v-else-if="scope.row.status == 2" trigger="hover" placement="left">
              <el-button type="success" @click="PickmanStatus(scope.row.id,1)">通过审核</el-button>
              <div slot="reference" class="name-wrapper">
                <el-tag size="warning">已通过</el-tag>
              </div>
            </el-popover> -->
            <el-popover v-else-if="scope.row.status == -1" trigger="hover" placement="left">
              <el-button type="success" @click="PickmanStatus(scope.row.id,1)">解冻账号</el-button>
              <div slot="reference" class="name-wrapper">
                <el-tag size="danger">冻结</el-tag>
              </div>
            </el-popover>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200px">
          <template slot-scope="scope">
            <!-- 分配区域 -->
            <el-button type="primary" icon="el-icon-edit" size="mini" @click="showAreaDialog(scope.row)" />
          </template>
        </el-table-column>
      </el-table>

      <!-- 分页区域 -->
      <el-pagination
        :current-page="queryInfo.pagenum"
        :page-sizes="[5, 10, 15, 20]"
        :page-size="queryInfo.pagesize"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
      />
    </el-card>

    <!-- 分配区域的对话框 -->
    <el-dialog title="分配区域" :visible.sync="areaDialogVisible" width="50%" @close="areaDialogClosed">
      <!-- 内容主体区域 -->
      <el-tree
        ref="areaTree"
        :data="areaTable"
        show-checkbox
        node-key="id"
        :props="areaProps"
      />
      <!-- 底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="areaDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="saveArea">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import pickmanApi from '@/api/pickman'
import areaApi from '@/api/area'

export default {
  data() {
    return {
      // 获取用户列表的参数对象
      queryInfo: {
        // 模糊查询真实姓名
        realname: '',
        // 模糊查询手机号
        phone: '',
        // 展示这个状态的用户
        status: '',
        // 当前的页数
        pagenum: 1,
        // 当前每页显示多少条数据
        pagesize: 10
      },
      // 用于加载取货员状态的查询框
      pickmanstatus: [{
        value: '-1',
        label: '冻结'
      }, {
        value: '1',
        label: '正常'
      }, {
        value: '0',
        label: '待审核'
      }
      ],
      userlist: [],
      total: 0,
      selectedPickmanId: null,
      areaTable: [],
      areaProps: {
        children: 'child',
        label: 'name'
      },
      areaDialogVisible: false
    }
  },
  created() {
    this.getPickmanList()
    areaApi.getAreaTable().then(response => {
      this.areaTable = response.data
    })
  },
  methods: {
    // 获取用户列表
    async getPickmanList() {
      const loading = this.$loading({
        lock: true,
        text: '列表加载中',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      })
      const data = await pickmanApi.getPickmanList(this.queryInfo)
      loading.close()
      if (data.code !== 10000) {
        return this.$message.error('获取用户列表失败！')
      }
      this.userlist = data.data.pickmanlist
      this.total = data.data.total
    },
    // 监听 pagesize 改变的事件
    handleSizeChange(newSize) {
      // console.log(newSize)
      this.queryInfo.pagesize = newSize
      this.getPickmanList()
    },
    // 监听 页码值 改变的事件
    handleCurrentChange(newPage) {
      console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getPickmanList()
    },
    // 监听添加用户对话框的关闭事件
    addDialogClosed() {
      this.$refs.addFormRef.resetFields()
    },
    async PickmanStatus(pickmanid, status) {
      var query = { 'id': pickmanid, 'status': status }
      const data = await pickmanApi.setPickmanStatus(query)
      if (data.code !== 10000) {
        return this.$message.error('获取用户列表失败！')
      }
      this.$message.success('修改取货员状态成功！')
      this.getPickmanList()
    },
    areaDialogClosed() {
      this.areaDialogVisible = false
    },
    showAreaDialog(pickman) {
      this.areaDialogVisible = true
      const areaIdList = []
      pickman.area.forEach(item => {
        areaIdList.push(item.id)
      })
      this.selectedPickmanId = pickman.id
      setTimeout(() => {
        this.$refs.areaTree.setCheckedKeys(areaIdList)
      }, 100)
    },
    saveArea() {
      const loading = this.$loading({
        lock: true
      })
      const areaIdList = this.$refs.areaTree.getCheckedKeys()
      pickmanApi.setArea({
        area_id: areaIdList.join(','),
        pickman_id: this.selectedPickmanId
      }).then(response => {
        this.$message.success(response.data.message || '修改成功')
        this.areaDialogVisible = false
        this.getPickmanList()
        loading.close()
      }).catch(() => {
        loading.close()
      })
    }
  }
}
</script>

<style lang="scss">
  .el-table {
    margin-top: 15px;
  }
</style>
