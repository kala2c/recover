<template>
  <div>
    <!-- 卡片视图区域 -->
    <el-card>
      <el-dialog title="新增地区" :visible.sync="addDialogVisible" width="50%" @close="addDialogClose">
        <!-- 内容主体区域 -->
        <el-form ref="addFormRef" :model="addForm" :rules="addFormRules" label-width="90px">
          <el-form-item label="名称" prop="name">
            <el-input v-model="addForm.name" />
          </el-form-item>
        </el-form>
        <!-- 底部区域 -->
        <span slot="footer" class="dialog-footer">
          <el-button @click="addDialogVisible = false">取 消</el-button>
          <el-button type="primary" @click="addArea">确 定</el-button>
        </span>
      </el-dialog>
      <!-- 列表区域 -->
      <el-tree
        :data="areaTable"
        node-key="id"
        node-expand="onExpand"
        :props="areaProps"
        :default-expanded-keys="expandedKeys"
        :expand-on-click-node="false"
      >
        <span slot-scope="{ node, data }" class="custom-tree-node area-node">
          <span class="content">{{ node.label }} </span>
          <span class="button-wrap">
            <el-button
              v-if="data.level > 1"
              type="text"
              size="mini"
              @click="appendArea(node, data)"
            >
              增加
            </el-button>
            <el-button
              v-if="data.level < 3"
              type="text"
              size="mini"
              @click="removeArea(node, data)"
            >
              删除
            </el-button>
          </span>
        </span>
      </el-tree>
    </el-card>
  </div>
</template>

<script>
import areaApi from '@/api/area'
export default {
  data() {
    return {
      // 控制添加地区对话框的显示与隐藏
      addDialogVisible: false,
      // 控制修改地区信息对话框的显示与隐藏
      setAdminDialogVisible: false,
      // 表单数据
      addForm: {
        name: ''
      },
      topAreaId: 0,
      setAdminForm: {
        area_id: null,
        admin_id: null
      },
      expandedKeys: [],
      addFormRules: {
        name: { required: true, message: '请输入地区名字', trigger: 'blur' }
      },
      areaTable: [],
      areaProps: {
        children: 'child',
        label: 'name'
      },
      adminList: []
    }
  },
  async created() {
    areaApi.getAdminList().then(response => {
      const data = response.data
      this.adminList = data
    })
    await this.getAreaTable()
    this.areaTable.forEach(item => {
      this.expandedKeys.push(item.id)
    })
  },
  methods: {
    async getAreaTable() {
      const loading = this.$loading({
        lock: true,
        text: '列表加载中',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      })
      const response = await areaApi.getAreaTable()
      this.areaTable = response.data
      loading.close()
    },
    onExpand(data) {
      this.expandNode(data.id)
    },
    expandNode(id) {
      if (this.expandedKeys.indexOf(id) === -1) {
        this.expandedKeys.push(id)
      }
    },
    addDialogClose() {
      this.$refs.addFormRef.resetFields()
      this.addDialogVisible = false
    },
    setAdminDialogClose() {
      this.setAdminDialogVisible = false
    },
    // 添加地区
    appendArea(node, data) {
      this.expandNode(data.id)
      this.topAreaId = data.id
      this.addDialogVisible = true
    },
    // 删除地区
    removeArea(node, data) {
      this.$confirm('该地区及下属地区将全部被删除', '删除地区', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        areaApi.removeArea({
          id: data.id
        }).then(response => {
          this.$message.success(response.data.message || '删除成功')
          this.getAreaTable()
        })
      }).catch(() => {
      })
    },
    setAdmin(node, data) {
      this.setAdminForm.area_id = data.id
      this.setAdminDialogVisible = true
    },
    addArea() {
      areaApi.appendArea({
        top_id: this.topAreaId,
        name: this.addForm.name
      }).then(response => {
        this.$message.success(response.data.message || '添加成功')
        this.addDialogClose()
        this.getAreaTable()
      })
    }
  }
}
</script>

<style lang="scss">
.el-table{
  margin-top: 15px;
}
.el-tree {
  width: 50%;
  margin: 10px auto;
}
.custom-tree-node {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
}
</style>
