<template>
  <el-container>
  <!--员工列表-->
    <el-table :data="tableData" style="width: 100%" height="500" ref="uTable">
      <el-table-column type="selection" width="55"></el-table-column>
      <el-table-column prop="name" label="姓名" width="180"></el-table-column>
      <el-table-column prop="tel" label="电话"></el-table-column>
      <el-table-column prop="date" label="最后登陆时间" sortable width="180"></el-table-column>
      <el-table-column prop="tag" label="类别" width="100"
        :filters="[{ text: '店长', value: '店长' }, { text: '管理员', value: '管理员' },{ text: '前台管理员', value: '前台管理员' }]"
        :filter-method="filterTag" filter-placement="bottom-end">
        <template slot-scope="scope">
          <el-tag :type="scope.row.tag === '店长' ? 'primary' : 'success'" close-transition>{{scope.row.tag}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row, 'Emp')">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-footer>
      <el-button @click="handledelSelect(selects, 'Emp')">删除所选</el-button>
      <el-button @click="handledelSelectAll('Emp')">删除全部</el-button>
    </el-footer>
    <el-dialog  title="修改员工信息" :visible.syc="dialogVisible" @close="handleCloseDialog">
      <el-form>
        <el-form-item>
          <el-input type="text" v-model="dialog.name"></el-input>
        </el-form-item>
        <el-form-item>
          <el-input type="text" v-model="dialog.tel"></el-input>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-select v-model="dialog.tag">
            <el-option v-for="item in tagData" :value="item.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-button type="info" @click="handleCloseDialog">取消</el-button>
          <el-button type="primary" @click="handleMakeEdit('Emp')">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </el-container>
</template>

<script>
  import http from '../../../assets/js/http'
  import common from '../../../assets/js/common'
  export default {
    data() {
      return {
        selects: [],
        tableData: [],
        dialogVisible: false,
        tagData: [],
        dialog: {}
      }
    },
    methods: {
      filterTag(value, row) {
        return row.tag === value;
      },
    },
    created () {
      this.apiPost('admin/base/findEmp').then(res => {
        //如果没有数据，或者请求失败的情况
        if(res.length == 0){
          this.$message.error('暂无数据，请先添加')
        }
        let tags = []
        res.forEach( (item, index) => {
          this.tableData.push({
            id: item.Emp_ID,
            name: item.Emp_Name,
            tel: item.Emp_Tel,
            date: item.Emp_LastDate,
            tag: item.Emp_Type
          })
        })
        tags.unshift('店长', '管理员', '前台管理员')
        tags = Array.from(new Set(tags))
        tags.forEach((item, index) => {
          this.tagData[index] = {
            text: item,
            value: item
          }
        })

      })
    },
    mixins:[http, common]
  }
</script>

<style scoped>

</style>
