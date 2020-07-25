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

      <!-- 废品种类列表区域 -->
      <el-table :data="wastelist" border stripe>
        <el-table-column type="index" />
        <el-table-column label="名称" prop="name" />
        <el-table-column label="分类" prop="wastekindname" />
        <el-table-column label="单位" prop="unit" />
        <el-table-column label="价格(元)" prop="price" />
        <el-table-column label="是否开启回收">
          <template slot-scope="scope">
            <el-switch
              v-model="scope.row.isrecover"
              active-value="1"
              inactive-value="0"
              @change="WasteIsRecoverChanged(scope.row.id,scope.row.isrecover)"
            />
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200px">
          <template slot-scope="scope">
            <!-- 修改按钮 -->
            <el-tooltip effect="dark" content="修改废品" placement="top" :enterable="false">
              <el-button type="primary" icon="el-icon-edit" size="mini" @click="showEditDialog(scope.row.id)" />
            </el-tooltip>
            <!-- 删除按钮 -->
            <el-tooltip effect="dark" content="删除废品" placement="top" :enterable="false">
              <el-button type="danger" icon="el-icon-delete" @click="deleteWaste(scope.row.id)" size="mini" />
            </el-tooltip>
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
      <el-form ref="addFormRef" :model="addForm" :rules="wasteFormRules" label-width="70px">
        <el-form-item label="名称" prop="name">
          <el-input v-model="addForm.name" />
        </el-form-item>
        <el-form-item label="分类" prop="wastekindid">
          <el-select v-model="addForm.wastekindid" placeholder="请选择废品分类">
            <el-option label="衣服" value="1"></el-option>
            <el-option label="纸壳" value="2"></el-option>
            <el-option label="塑料" value="3"></el-option>
            <el-option label="数码产品" value="4"></el-option>
            <el-option label="家电产品" value="5"></el-option>
            <el-option label="金属产品" value="6"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="单位" prop="unit">
          <el-input v-model="addForm.unit" />
        </el-form-item>
        <el-form-item label="价格" prop="price">
          <el-input v-model="addForm.price" />
        </el-form-item>
        <!--        <el-form-item label="是否开启回收" prop="mobile">-->
        <!--          <el-input v-model="addForm.mobile" />-->
        <!--        </el-form-item>-->
      </el-form>
      <!-- 底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="addDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="addWaste">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 修改废品的对话框-->
    <el-dialog
      title="修改废品信息"
      :visible.sync="editDialogVisible"
      :close-on-click-modal="false"
      width="40%"
    >
      <el-form ref="editFormRef" :model="editForm" :rules="wasteFormRules" label-width="70px">
        <el-form-item label="名称" prop="name">
          <el-input v-model="editForm.name" />
        </el-form-item>
        <el-form-item label="分类" prop="wastekindid">
          <el-select v-model="editForm.wastekindid" placeholder="请选择废品分类">
            <el-option label="衣服" value="1"></el-option>
            <el-option label="纸壳" value="2"></el-option>
            <el-option label="塑料" value="3"></el-option>
            <el-option label="数码产品" value="4"></el-option>
            <el-option label="家电产品" value="5"></el-option>
            <el-option label="金属产品" value="6"></el-option>
          </el-select>
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
      // 添加的表单数据
      addForm: {
        name: '',
        price: '',
        unit: '',
        wastekindid: ''
      },
      editForm: {},
      // 废品表单的验证规则
      wasteFormRules: {
        name: [
          { required: true, message: '请输入废品名称', trigger: 'blur' }
        ],
        wastekindid: [
          { required: true, message: '请选择废品分类', trigger: 'blur' }
        ]
        // price: [
        //   { required: true, message: '请输入废品单价', trigger: 'blur' }
        // {
        //   validator(rule, value, callback) {
        //     var reg = /^-?\d{1,5}(?:\.\d{1,2})?$/
        //     if (reg.test(value)) {
        //       callback()
        //     } else {
        //       callback(new Error('请输入大于零小于十万且不超过两位小数的数字'))
        //     }
        //   },
        //   trigger: 'change'
        // }
        // ]
      }
    }
  },
  created() {
    this.getWasteList()
  },
  methods: {
    async getWasteList() {
      const data = await wasteApi.getWasteList(this.queryInfo)
      // console.log(data)
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
      // console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getWasteList()
    },
    // 监听 switch 开关状态的改变
    async userStateChanged(userinfo) {
      // console.log(userinfo)
      const { data: res } = await this.$http.put(
        `users/${userinfo.id}/state/${userinfo.mg_state}`
      )
      if (res.meta.status !== 200) {
        userinfo.mg_state = !userinfo.mg_state
        return this.$message.error('更新回收状态失败！')
      }
      this.$message.success('更新回收状态成功！')
    },
    // 监听添加废品对话框的关闭事件
    addDialogClosed() {
      this.$refs.addFormRef.resetFields()
    },
    // 点击按钮，添加新废品
    addWaste() {
      this.$refs.addFormRef.validate(async valid => {
        if (!valid) return
        const data = await wasteApi.addWaste(this.addForm)
        if (data.code !== 10000) {
          this.$message.error('添加废品失败！')
        }
        this.$message.success('添加废品成功！')
        // 隐藏添加用户的对话框
        this.addDialogVisible = false
        // 重新获取用户列表数据
        this.getWasteList()
      })
    },
    async showEditDialog(id) {
      var query = { 'id': id }
      const data = await wasteApi.getWasteInfo(query)
      if (data.code !== 10000) {
        this.$message.error('获取废品信息失败！')
      }
      this.editForm = data.data.wasteinfo
      this.editForm.wastekindid = this.editForm.wastekindid.toString()
      this.editDialogVisible = true
    },
    // 修改废品信息
    async editWasteInfo() {
      this.$refs.editFormRef.validate(async valid => {
        if (!valid) return
        const data = await wasteApi.setWasetInfo(this.editForm)
        if (data.code !== 10000) {
          this.$message.error('修改废品信息失败！')
        }
        this.getWasteList()
        this.editDialogVisible = false
      })
    },
    deleteWaste(id) {
      this.$confirm('是否要删除该废品？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(async() => {
        var query = { 'id': id }
        const data = await wasteApi.deleteWaste(query)
        if (data.code !== 10000) {
          this.$message.error('删除废品失败！')
        }
        this.getWasteList()
        this.$message({
          type: 'success',
          message: '删除成功!'
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    async WasteIsRecoverChanged(id, isrecover) {
      var query = { 'id': id }
      const data = await wasteApi.getWasteInfo(query)
      if (data.code !== 10000) {
        this.$message.error('获取废品信息失败！')
      }
      this.editForm = data.data.wasteinfo
      this.editForm.isrecover = isrecover
      const res = await wasteApi.setWasetInfo(this.editForm)
      if (res.code !== 10000) {
        this.$message.error('修改回收状态失败！')
      }
      this.getWasteList()
      this.$message({
        type: 'success',
        message: '修改回收状态成功!'
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
