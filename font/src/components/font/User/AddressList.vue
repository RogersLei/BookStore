<template>
  <div>
    <el-button type="danger" @click="showDialog" style="margin-top: 10px">添加收货信息</el-button>
    <el-table
      ref="addTable"
      :data="address"
      max-height="522"
      tooltip-effect="dark"
      style="width: 100%"
    >
      <el-table-column
        type="selection"
        align="center"
        width="120">
      </el-table-column>
      <el-table-column
        prop="name"
        label="收货人"
        align="center"
        width="120"
      >
      </el-table-column>
      <el-table-column
        prop="tel"
        label="电话"
        align="center"
        width="180">
      </el-table-column>
      <el-table-column
        prop="address"
        label="地址"
        align="center"
        show-overflow-tooltip
      >
      </el-table-column>
      <el-table-column label="操作" align="center">
        <template slot-scope="scope">
          <!--<el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>-->
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog  title="登陆" :visible.syc="dialogVisible" @close="handleCloseDialog">
      <el-form :model="dialog" ref="login" label-width="120px">
        <el-form-item label="收货人" prop="account">
          <el-input type="text" v-model="dialog.name"></el-input>
        </el-form-item>
        <el-form-item label="电话" prop="pass">
          <el-input type="text" v-model="dialog.tel"></el-input>
        </el-form-item>
        <el-form-item label="地址" prop="pass">
          <el-input type="text" v-model="dialog.address"></el-input>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-button type="info" @click="handleCloseDialog">取消</el-button>
          <el-button type="primary" @click="add">增加</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
  import http from '../../../assets/js/common'
  export default {
    name: "address-list",
    data() {
      return {
        dialogVisible: false,
        dialog: {
          name: '',
          tel: '',
          address: ''
        },
      }
    },
    props: ['address'],
    methods: {
      add () {
        let account = JSON.parse(sessionStorage.getItem('user')).account
        this.dialog.account = account
        if(this.dialog.name ==='' || this.dialog.tel ==='' || this.dialog.address ===''){
          this.$message.warning('请正确填写信息')
          return
        }
        if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(this.dialog.tel))){
          this.$message.error("请输入正确的手机号");
          return
        }
        this.apiPost('font/base/insertAddress', this.dialog).then((res)=>{
          if(res.code === 200){
            this.$message.success('添加成功')
            this.$router.go(this.$route.path)
          } else {
            this.$message.error('添加失败')
          }
        })
      },
      handleDelete (index, row) {
        let account = JSON.parse(sessionStorage.getItem('user')).account
        let obj = Object.assign({},{account: account}, row)
        console.log(obj)
        this.apiPost('font/base/deleteAddress', obj).then((res)=>{
          if(res.code === 200){
            this.$message.success('删除成功')
            this.$router.go(this.$route.path)
          } else {
            this.$message.error('删除失败')
          }
        })
      },
      showDialog () {
        this.dialogVisible = true
      }
    },
    mixins: [http]
  }
</script>

<style scoped>

</style>
