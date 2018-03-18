<template>
  <div>
    <el-row class="row">
      <el-col :span="6"><span style="">个人头像:</span></el-col>
      <el-col :span="9"><img :src="obj.src" alt="" /></el-col>
      <el-col :span="9"><el-button type="primary" disabled>更换头像</el-button></el-col>
    </el-row>
    <el-row class="row">
      <el-col :span="6"><span style="">昵称：</span></el-col>
      <el-col :span="9"><el-input v-model="obj.name"></el-input></el-col>
    </el-row>
    <el-row class="row">
      <el-col :span="6"><span style="">修改密码：</span></el-col>
      <el-col :span="9"><el-input type="password" v-model="obj.pass"></el-input></el-col>
    </el-row>
    <el-row class="row">
      <el-col :span="24"><el-button type="danger" @click="submit">更改信息</el-button></el-col>
    </el-row>

  </div>
</template>

<script>
  import http from '../../../assets/js/http'
  export default {
    name: "info",
    data () {
      return {
        obj: Object.assign({},JSON.parse(sessionStorage.getItem('user')),{pass:''}),
      }
    },
    // props: ['user'],
    methods: {
      submit () {
        this.apiPost('font/base/updateUser', this.obj).then((res)=>{
          if(res.code === 200){
            this.$message.success('修改信息成功');
            this.obj.pass = ''
            delete this.obj.pass
            sessionStorage.setItem('user', JSON.stringify(this.obj))
            this.$emit('change',this.obj)
          } else {
            this.$message.error('修改信息失败');
          }
        })
      }
    },
    mounted(){

    },
    mixins: [http]
  }
</script>

<style scoped>
  img{
    display: inline-block;
    margin-left: 28px;
    height: 100px;
    width: 100px;
    float: left;
    padding: 10px 0;
  }
  .row {
    box-sizing: border-box;
    margin-top:20px;

    border-bottom: 1px solid #c1c1c1;
    height: 120px;
    line-height: 120px
  }
</style>
