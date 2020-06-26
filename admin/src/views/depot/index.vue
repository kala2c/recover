<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="5">
          <el-input
            v-model="queryInfo.username"
            placeholder="搜索用户名"
            clearable
            @change="getDepotList"
            @clear="getDepotList"
          >
            <el-button slot="append" icon="el-icon-search" @click="getDepotList" />
          </el-input>
        </el-col>
        <el-col :span="5">
          <el-input
            v-model="queryInfo.mobile"
            placeholder="搜索手机号"
            clearable
            @change="getDepotList"
            @clear="getDepotList"
          >
            <el-button slot="append" icon="el-icon-search" @click="getDepotList" />
          </el-input>
        </el-col>
        <el-col :span="4">
          <el-button type="primary" @click="addDialogVisible = true">新增站点</el-button>
        </el-col>
        <!-- <el-col :span="5">
          <el-select v-model="queryInfo.status" placeholder="站点状态" @change="getDepotList">
            <el-option
              v-for="item in depotstatus"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-col> -->
      </el-row>

      <!-- 列表区域 -->
      <el-table :data="userlist" border stripe>
        <el-table-column type="index" />
        <el-table-column label="用户名" prop="username" />
        <el-table-column label="openid" prop="openid" width="300px" />
        <el-table-column label="手机" prop="mobile" />
        <el-table-column label="身份信息" prop="note" />
        <el-table-column label="区域">
          <template slot-scope="scope">
            <!-- 区域 -->
            <span>{{ scope.row.area && scope.row.area.name || '未分配区域' }}</span>
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

    <!-- 添加对话框 -->
    <el-dialog title="添加回收站点" :visible.sync="addDialogVisible" width="40%" @close="addDialogClosed">
      <p>站点新增后，默认显示地区为您负责的县（区），需手动关联到小区</p>
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
        <!-- <el-form-item label="绑定小区" prop="community">
          <el-input v-model="searchCommunityKey" size="meduim" suffix-icon="el-icon-search" />
          <div>
            <div
              v-for="community in communitySearchResult"
              :key="community.id"
            >
              <span @click="choseCommunity(community)">{{ community.name }}</span>
            </div>
          </div>

        </el-form-item> -->
        <!-- <el-form-item label="选择街道" prop="community">
          <el-input v-model="searchStreetKey" size="meduim" suffix-icon="el-icon-search" />
          <div>
            <div
              v-for="street in streetSearchResult"
              :key="street.id"
            >
              {{ street.name }}
            </div>
          </div>
        </el-form-item> -->
      </el-form>
      <!-- 底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="addDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="addDepot">确 定</el-button>
      </span>
    </el-dialog>

    <!-- 分配区域的对话框 -->
    <el-dialog title="分配区域" :visible.sync="areaDialogVisible" width="50%" @close="areaDialogClosed">
      <!-- 内容主体区域 -->
      <el-tree
        ref="areaTree"
        show-checkbox
        node-key="id"
        :props="areaProps"
        :data="areaTable"
        @check="clickTree"
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
import depotApi from '@/api/depot'
import areaApi from '@/api/area'

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
      // 获取用户列表的参数对象
      queryInfo: {
        // 模糊查询真实姓名
        username: '',
        // 模糊查询手机号
        mobile: '',
        // 展示这个状态的用户
        status: '',
        // 当前的页数
        pagenum: 1,
        // 当前每页显示多少条数据
        pagesize: 10
      },
      userlist: [],
      total: 0,
      selectedDepotId: null,
      areaTable: [],
      areaProps: {
        children: 'child',
        label: 'name'
      },
      searchCommunityKey: '',
      searchStreetKey: '',
      communityChosen: null,
      // 搜索小区的结果
      communitySearchResult: [],
      // 搜索街道的结果
      streetSearchResult: [],
      // 添加用户的表单数据
      addForm: {
        username: '',
        password: '',
        note: '',
        mobile: ''
      },
      // 表单的验证规则对象
      formRules: {
        username: [
          { required: true, message: '请输入登录账号', trigger: 'blur' },
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
      },
      addDialogVisible: false,
      areaDialogVisible: false,
      node: null,
      nodeisChecked: false
    }
  },
  watch: {
    searchCommunityKey(type) {
      this.searchArea('community')
    },
    searchStreetKey(type) {
      this.searchArea('street')
    }
  },
  created() {
    this.getDepotList()
    areaApi.getAreaTable().then(response => {
      this.areaTable = response.data
    })
  },
  methods: {
    // 获取用户列表
    async getDepotList() {
      const loading = this.$loading({
        lock: true,
        text: '列表加载中',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      })
      const data = await depotApi.getDepotList(this.queryInfo)
      loading.close()
      if (data.code !== 10000) {
        return this.$message.error('获取用户列表失败！')
      }
      this.userlist = data.data.depotlist
      this.total = data.data.total
    },
    // 监听 pagesize 改变的事件
    handleSizeChange(newSize) {
      // console.log(newSize)
      this.queryInfo.pagesize = newSize
      this.getDepotList()
    },
    // 监听 页码值 改变的事件
    handleCurrentChange(newPage) {
      console.log(newPage)
      this.queryInfo.pagenum = newPage
      this.getDepotList()
    },
    // 监听添加回收站对话框中搜索街道/小区事件
    searchArea(type) {
      const key =
        type === 'street'
          ? this.searchStreetKey
          : this.searchCommunityKey
      if (!key) return
      const params = {
        key: key,
        type: type
      }
      depotApi.searchArea(params).then(response => {
        const data = response.data
        if (type === 'street') {
          this.streetSearchResult = data
        } else {
          this.communitySearchResult = data
        }
      })
    },
    choseCommunity(community) {
      this.communityChosen = community
    },
    // 监听添加用户对话框的关闭事件
    addDialogClosed() {
      this.$refs.addFormRef.resetFields()
    },
    // 点击按钮
    addDepot() {
      this.$refs.addFormRef.validate(async valid => {
        if (!valid) return
        const data = await depotApi.append(this.addForm)
        if (data.code !== 10000) {
          this.$message.error('添加失败')
        }
        // 隐藏添加用户的对话框
        this.addDialogVisible = false
        // 重新获取用户列表数据
        this.getDepotList()
      })
    },
    async depotStatus(depotid, status) {
      var query = { 'id': depotid, 'status': status }
      const data = await depotApi.setdepotStatus(query)
      if (data.code !== 10000) {
        return this.$message.error('获取列表失败！')
      }
      this.$message.success('修改状态成功！')
      this.getDepotList()
    },
    areaDialogClosed() {
      this.areaDialogVisible = false
    },
    clickTree(node, tree) {
      console.log(node)
      // if (tree.checkedKeys.length === 0) {
      //   this.$refs.areaTree.setCheckedKeys([])
      //   this.node = node
      //   this.nodeisChecked = false
      // } else {
      this.$refs.areaTree.setCheckedKeys([])
      this.$refs.areaTree.setCheckedKeys([node.id])
      this.node = node
      console.log(node)
      this.nodeisChecked = true
      // }
    },
    // 显示回收点分配区域
    showAreaDialog(depot) {
      this.areaDialogVisible = true
      // 渲染默认选中
      const areaIdList = []
      areaIdList.push(depot.area && depot.area.id)
      this.selectedDepotId = depot.id
      setTimeout(() => {
        this.$refs.areaTree.setCheckedKeys(areaIdList)
        this.nodeisChecked = true
      }, 100)
    },
    // 修改回收点区域
    saveArea() {
      const loading = this.$loading({
        lock: true
      })
      depotApi.setArea({
        area_id: this.node.id,
        depot_id: this.selectedDepotId
      }).then(response => {
        this.$message.success(response.data.message || '修改成功')
        this.areaDialogVisible = false
        this.getDepotList()
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
