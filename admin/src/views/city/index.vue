<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="8">
          <el-input v-model="queryInfo.query" placeholder="搜索城市" clearable @clear="getCityList">
            <el-button slot="append" icon="el-icon-search" @click="getCityList" />
          </el-input>
        </el-col>
        <el-col :span="4">
          <el-button type="primary" @click="addDialogVisible = true">新增城市</el-button>
        </el-col>
      </el-row>

      <!-- 列表区域 -->
      <el-table :data="citylist" border stripe>
        <el-table-column type="index" />
        <el-table-column label="名字" prop="name" />
        <el-table-column label="备注" prop="note" />
        <el-table-column label="管理员" prop="administrator.note" />
        <el-table-column label="操作" width="200px">
          <template slot-scope="scope">
            <!-- 修改按钮 -->
            <el-button type="primary" icon="el-icon-edit" size="mini" @click="showEditDialog(scope.row)" />
            <!-- 禁用按钮 -->
            <!-- <el-button type="danger" icon="el-icon-circle-close" size="mini" /> -->
          </template>
        </el-table-column>
      </el-table>

      <!-- 分页区域 -->
      <el-pagination
        :current-page="queryInfo.pagenum"
        :total="total"
        :page-sizes="[5, 10, 15, 20]"
        :page-size="queryInfo.pagesize"
        layout="total, sizes, prev, pager, next, jumper"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
      />
    </el-card>

    <!-- 添加对话框 -->
    <el-dialog title="添加城市" :visible.sync="addDialogVisible" width="50%" @close="addDialogClosed">
      <!-- 内容主体区域 -->
      <el-form ref="addFormRef" :model="addForm" :rules="formRules" label-width="90px">
        <el-form-item label="城市名字" prop="name">
          <el-input v-model="addForm.name" />
        </el-form-item>
        <el-form-item label="城市备注" prop="note">
          <el-input v-model="addForm.note" />
        </el-form-item>
        <!-- <el-form-item label="关联手机" prop="mobile">
          <el-input v-model="addForm.mobile" />
        </el-form-item> -->
      </el-form>
      <!-- 底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="addDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="addUser">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 修改对话框-->
    <el-dialog
      title="修改城市信息"
      :visible.sync="editDialogVisible"
      width="40%"
    >
      <el-form ref="editFormRef" label-width="90px">
        <el-form-item label="城市名字" prop="name">
          <el-input v-model="editForm.name" />
        </el-form-item>
        <el-form-item label="城市备注" prop="note">
          <el-input v-model="editForm.note" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="editDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="editAdminInfo">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import cityApi from '@/api/city'
export default {
  data() {
    return {
      // 获取列表的参数对象
      queryInfo: {
        // 用于模糊搜索
        query: '',
        // 当前的页数
        pagenum: 1,
        // 当前每页显示多少条数据
        pagesize: 10
      },
      citylist: [],
      total: 0,
      // 控制添加废品对话框的显示与隐藏
      addDialogVisible: false,
      // 控制修改废品信息对话框的显示与隐藏
      editDialogVisible: false,
      // 添加用户的表单数据
      addForm: {
        name: '',
        note: ''
      },
      editForm: {
        id: '',
        name: '',
        note: ''
      },
      // 表单的验证规则对象
      formRules: {
        name: [
          { required: true, message: '请输入名字', trigger: 'blur' }
        ],
        note: [
          { required: true, message: '请输入备注', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.getCityList()
  },
  methods: {
    async getCityList() {
      const data = await cityApi.getList(this.queryInfo)
      console.log(data)
      if (data.code !== 10000) {
        return this.$message.error('获取列表失败')
      }
      this.citylist = data.data.list
      this.total = data.data.meta.total
    },
    // 监听 pagesize 改变的事件
    handleSizeChange(newSize) {
      // console.log(newSize)
      this.queryInfo.pagesize = newSize
      this.getCityList()
    },
    // 监听 页码值 改变的事件
    handleCurrentChange(newPage) {
      console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getCityList()
    },
    // 监听添加用户对话框的关闭事件
    addDialogClosed() {
      this.$refs.addFormRef.resetFields()
    },
    // 点击按钮
    addUser() {
      this.$refs.addFormRef.validate(async valid => {
        if (!valid) return
        const data = await cityApi.append(this.addForm)
        if (data.code !== 10000) {
          this.$message.error('添加失败')
        }
        // 隐藏添加用户的对话框
        this.addDialogVisible = false
        // 重新获取用户列表数据
        this.getCityList()
      })
    },
    // 修改信息
    async showEditDialog(admin) {
      this.editForm = admin
      this.editDialogVisible = true
    },
    async editAdminInfo() {
      const data = await cityApi.setInfo(this.editForm)
      if (data.code !== 10000) {
        this.$message.error('修改信息失败')
      }
      this.getCityList()
      this.editDialogVisible = false
    }
  }
}
</script>

<style lang="scss">
.el-table{
    margin-top: 15px;
}
</style>
