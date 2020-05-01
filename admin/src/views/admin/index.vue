<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="8">
          <el-input v-model="queryInfo.query" placeholder="搜索管理员" clearable @clear="getAdminList">
            <el-button slot="append" icon="el-icon-search" @click="getAdminList" />
          </el-input>
        </el-col>
        <el-col :span="4">
          <el-button type="primary" @click="addDialogVisible = true">新增管理员</el-button>
        </el-col>
      </el-row>

      <!-- 列表区域 -->
      <el-table :data="wastelist" border stripe>
        <el-table-column type="index" />
        <el-table-column label="登录账号" prop="username" />
        <el-table-column label="手机号" prop="mobile" />
        <el-table-column label="身份信息" prop="note" />
        <el-table-column label="操作" width="200px">
          <template slot-scope="scope">
            <!-- 修改按钮 -->
            <el-button type="primary" icon="el-icon-edit" size="mini" @click="showEditDialog(scope.row)" />
            <!-- 删除按钮 -->
            <el-button type="danger" icon="el-icon-delete" size="mini" />
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
    <el-dialog title="添加负责人" :visible.sync="addDialogVisible" width="50%" @close="addDialogClosed">
      <!-- 内容主体区域 -->
      <el-form ref="addFormRef" :model="addForm" :rules="formRules" label-width="90px">
        <el-form-item label="登录账号" prop="username">
          <el-input v-model="addForm.username" />
        </el-form-item>
        <el-form-item label="登录密码" prop="password">
          <el-input v-model="addForm.password" />
        </el-form-item>
        <el-form-item label="关联手机" prop="mobile">
          <el-input v-model="addForm.mobile" />
        </el-form-item>
        <el-form-item label="身份信息" prop="note">
          <el-input v-model="addForm.note" />
        </el-form-item>
      </el-form>
      <!-- 底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="addDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="addUser">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 修改对话框-->
    <el-dialog
      title="修改废品信息"
      :visible.sync="editDialogVisible"
      width="40%"
    >
      <el-form ref="editFormRef" label-width="90px">
        <el-form-item label="登录账号" prop="username">
          <el-input v-model="editForm.username" />
        </el-form-item>
        <!-- <el-form-item label="登录密码" prop="password">
          <el-input v-model="editForm.password" />
        </el-form-item> -->
        <el-form-item label="关联手机" prop="mobile">
          <el-input v-model="editForm.mobile" />
        </el-form-item>
        <el-form-item label="身份信息" prop="note">
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
import adminApi from '@/api/administrator'
export default {
  data() {
    // 验证手机号的规则
    var checkMobile = (rule, value, cb) => {
      // 验证手机号的正则表达式
      const regMobile = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/

      if (regMobile.test(value)) {
        return cb()
      }

      cb(new Error('请输入合法的手机号'))
    }

    return {
      // 获取废品列表的参数对象
      queryInfo: {
        query: '',
        // 当前的页数
        pagenum: 1,
        // 当前每页显示多少条数据
        pagesize: 10
      },
      wastelist: [],
      total: 0,
      // 控制添加废品对话框的显示与隐藏
      addDialogVisible: false,
      // 控制修改废品信息对话框的显示与隐藏
      editDialogVisible: false,
      // 添加用户的表单数据
      addForm: {
        username: '',
        password: '',
        note: '',
        mobile: ''
      },
      editForm: {
        id: '',
        username: '',
        // password: '',
        note: '',
        mobile: ''
      },
      // 表单的验证规则对象
      formRules: {
        username: [
          { required: true, message: '请输入用户名', trigger: 'blur' },
          {
            min: 3,
            max: 10,
            message: '用户名的长度在3~10个字符之间',
            trigger: 'blur'
          }
        ],
        password: [
          { required: true, message: '请输入密码', trigger: 'blur' },
          {
            min: 6,
            max: 15,
            message: '用户名的长度在6~15个字符之间',
            trigger: 'blur'
          }
        ],
        note: [
          { required: true, message: '请输入身份备注', trigger: 'blur' }
        ],
        mobile: [
          { required: true, message: '请输入手机号', trigger: 'blur' },
          { validator: checkMobile, trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.getAdminList()
  },
  methods: {
    async getAdminList() {
      const data = await adminApi.getList(this.queryInfo)
      console.log(data)
      if (data.code !== 10000) {
        return this.$message.error('获取列表失败')
      }
      this.wastelist = data.data.list
      this.total = data.data.meta.total
    },
    // 监听 pagesize 改变的事件
    handleSizeChange(newSize) {
      // console.log(newSize)
      this.queryInfo.pagesize = newSize
      this.getAdminList()
    },
    // 监听 页码值 改变的事件
    handleCurrentChange(newPage) {
      console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getAdminList()
    },
    // 监听添加用户对话框的关闭事件
    addDialogClosed() {
      this.$refs.addFormRef.resetFields()
    },
    // 点击按钮
    addUser() {
      this.$refs.addFormRef.validate(async valid => {
        if (!valid) return
        const data = await adminApi.append(this.addForm)
        if (data.code !== 10000) {
          this.$message.error('添加失败')
        }
        // 隐藏添加用户的对话框
        this.addDialogVisible = false
        // 重新获取用户列表数据
        this.getAdminList()
      })
    },
    // 修改信息
    async showEditDialog(admin) {
      this.editForm = admin
      this.editDialogVisible = true
    },
    async editAdminInfo() {
      const data = await adminApi.setInfo(this.editForm)
      if (data.code !== 10000) {
        this.$message.error('修改信息失败')
      }
      this.getAdminList()
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
