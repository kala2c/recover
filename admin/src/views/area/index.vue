<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="8">
          <el-input v-model="queryInfo.query" placeholder="搜索废品名称" clearable @clear="getWasteList">
            <el-button slot="append" icon="el-icon-search" @click="getWasteList" />
          </el-input>
        </el-col>
        <el-col :span="4">
          <el-button type="primary" @click="addDialogVisible = true">新增废品回收</el-button>
        </el-col>
      </el-row>

      <!-- 列表区域 -->
      <el-table :data="wastelist" border stripe>
        <el-table-column type="index" />
        <el-table-column label="名称" prop="name" />
        <el-table-column label="单位" prop="unit" />
        <el-table-column label="价格(元)" prop="price" />
        <el-table-column label="是否开启回收">
          <template slot-scope="scope">
            <el-switch
              v-model="scope.row.isrecover"
              active-value="1"
              inactive-value="0"
              @change="WasteIsRecoverChanged(scope.row.id)"
            />
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200px">
          <template slot-scope="scope">
            <!-- 修改按钮 -->
            <el-button type="primary" icon="el-icon-edit" size="mini" @click="showEditDialog(scope.row.id)" />
            <!-- 删除按钮 -->
            <el-button type="danger" icon="el-icon-delete" size="mini" />
            <!-- 分配角色按钮 -->
            <el-tooltip effect="dark" content="分配角色" placement="top" :enterable="false">
              <el-button type="warning" icon="el-icon-setting" size="mini" />
            </el-tooltip>
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

    <!-- 添加废品的对话框 -->
    <el-dialog title="添加废品" :visible.sync="addDialogVisible" width="50%" @close="addDialogClosed">
      <!-- 内容主体区域 -->
      <el-form ref="addFormRef" :model="addForm" :rules="addFormRules" label-width="70px">
        <el-form-item label="名称" prop="username">
          <el-input v-model="addForm.username" />
        </el-form-item>
        <el-form-item label="单位" prop="password">
          <el-input v-model="addForm.password" />
        </el-form-item>
        <el-form-item label="价格" prop="email">
          <el-input v-model="addForm.email" />
        </el-form-item>
        <el-form-item label="是否开启回收" prop="mobile">
          <el-input v-model="addForm.mobile" />
        </el-form-item>
      </el-form>
      <!-- 底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="addDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="addUser">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 修改废品的对话框-->
    <el-dialog
      title="修改废品信息"
      :visible.sync="editDialogVisible"
      width="40%"
    >
      <el-form ref="editFormRef" :model="editForm" :rules="editFormRules" label-width="70px">
        <el-form-item label="名称" prop="name">
          <el-input v-model="editForm.name" />
        </el-form-item>
        <el-form-item label="图片" prop="image">
          <el-input v-model="editForm.image" />
        </el-form-item>
        <el-form-item label="单位" prop="unit">
          <el-input v-model="editForm.unit" />
        </el-form-item>
        <el-form-item label="价格" prop="price">
          <el-input v-model="editForm.price" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="editDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="editWasteInfo">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import wasteApi from '@/api/waste'
export default {
  data() {
    // 验证邮箱的规则
    var checkEmail = (rule, value, cb) => {
      // 验证邮箱的正则表达式
      const regEmail = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/

      if (regEmail.test(value)) {
        // 合法的邮箱
        return cb()
      }

      cb(new Error('请输入合法的邮箱'))
    }

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
        email: '',
        mobile: ''
      },
      editForm: {},
      // 添加表单的验证规则对象
      addFormRules: {
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
        email: [
          { required: true, message: '请输入邮箱', trigger: 'blur' },
          { validator: checkEmail, trigger: 'blur' }
        ],
        mobile: [
          { required: true, message: '请输入手机号', trigger: 'blur' },
          { validator: checkMobile, trigger: 'blur' }
        ]
      },
      // 修改废品表单的验证规则
      editFormRules: {
        name: [
          { required: true, message: '请输入废品名称', trigger: 'blur' }
        ],
        price: [
          { required: true, message: '请输入废品单价', trigger: 'blur' }
        ],
        unit: [
          { required: true, message: '请输入废品单位', trigger: 'blur' }
        ]
      }
    }
  },
  created() {
    this.getWasteList()
  },
  methods: {
    async getWasteList() {
      const data = await wasteApi.getWasteList(this.queryInfo)
      console.log(data)
      if (data.code !== 10000) {
        return this.$message.error('获取废品列表失败！')
      }
      this.wastelist = data.data.wastelist
      this.total = data.data.total
    },
    // 监听 pagesize 改变的事件
    handleSizeChange(newSize) {
      // console.log(newSize)
      this.queryInfo.pagesize = newSize
      this.getWasteList()
    },
    // 监听 页码值 改变的事件
    handleCurrentChange(newPage) {
      console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getWasteList()
    },
    // 监听 switch 开关状态的改变
    async userStateChanged(userinfo) {
      console.log(userinfo)
      const { data: res } = await this.$http.put(
        `users/${userinfo.id}/state/${userinfo.mg_state}`
      )
      if (res.meta.status !== 200) {
        userinfo.mg_state = !userinfo.mg_state
        return this.$message.error('更新用户状态失败！')
      }
      this.$message.success('更新用户状态成功！')
    },
    // 监听添加用户对话框的关闭事件
    addDialogClosed() {
      this.$refs.addFormRef.resetFields()
    },
    // 点击按钮，添加新用户
    addUser() {
      this.$refs.addFormRef.validate(async valid => {
        if (!valid) return
        // 可以发起添加用户的网络请求
        const { data: res } = await this.$http.post('users', this.addForm)

        if (res.meta.status !== 201) {
          this.$message.error('添加用户失败！')
        }

        this.$message.success('添加用户成功！')
        // 隐藏添加用户的对话框
        this.addDialogVisible = false
        // 重新获取用户列表数据
        this.getWasteList()
      })
    },
    // 修改废品信息
    async showEditDialog(id) {
      var query = { 'id': id }
      const data = await wasteApi.getWasteInfo(query)
      if (data.code !== 10000) {
        this.$message.error('添加废品失败！')
      }
      this.editForm = data.data.wasteinfo
      this.editDialogVisible = true
    },
    async editWasteInfo() {
      const data = await wasteApi.setWasetInfo(this.editForm)
      if (data.code !== 10000) {
        this.$message.error('修改废品信息失败！')
      }
      this.getWasteList()
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
