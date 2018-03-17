<template>
  <el-form label-position="left" label-width="0px" class=" login-container">
    <el-form-item style="width:100%;">
      <el-button type="" style="width:100%; margin-left: 0; margin-bottom: 10px;" @click="handleGoHome">进入书店官网</el-button>
    </el-form-item>
    <h3 class="title">书店管理系统登录</h3>
    <el-form-item prop="account">
      <el-input type="text" v-model="ruleForm2.account" auto-complete="off" placeholder="账号" name="account"></el-input>
    </el-form-item>
    <el-form-item prop="checkPass">
      <el-input type="password" v-model="ruleForm2.checkPass" auto-complete="off" placeholder="密码"></el-input>
    </el-form-item>
    <el-checkbox v-model="checked" checked class="remember">记住密码</el-checkbox>
    <el-form-item style="width:100%;">
      <el-button type="primary" style="width:100%;" @click="LoginAdmin" :loading="logining">登录</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
  import http from '../../assets/js/http'
  import common from '../../assets/js/common'
export default {
  data() {
    return {
      logining: false,
      ruleForm2: {},
      checked: true  // 复选框 选中后将账号密码存入记录中
    };
  },
  methods: {
    LoginAdmin() {
      let emp = {
        account: this.ruleForm2.account,
        checkPass: this.ruleForm2.checkPass,
      }
      let Now = new Date()
      let Y = Now.getFullYear()
      let M = '0' + (Now.getMonth()+1)
      let D = '0' + (Now.getDate())
      let h = '0' + (Now.getHours())
      let m = '0' + (Now.getMinutes())
      let s = '0' + (Now.getSeconds())

      let date = Y +'-' + M.substring(M.length-2, M.length) + '-'+D.substring(D.length-2, D.length) + ' '
        + h.substring(h.length-2, h.length) + ':' + m.substring(m.length-2, m.length) + ':'
        + s.substring(s.length-2, s.length)
      emp.time = date
      let _this = this
      this.apiPost('admin/base/login',emp).then((res) => {
        if(res.code == 200){
          emp.power = res.power
          sessionStorage.setItem('emp', JSON.stringify(emp));
          if(this.checked){
            localStorage.setItem('emp', JSON.stringify(emp));
          }
          _this.$router.push({
            path: '/userL'
          })
        } else {
          this.$message.error(res.msg)
        }

      })

    },
    loadEmp() {
      let emp = JSON.parse(localStorage.getItem('emp'))
      if(emp != null){
        this.ruleForm2 = {
          'account': emp.account,
          'checkPass': emp.checkPass
        }
      }

    }
  },
  mounted() {
    this.loadEmp()
  },
  mixins: [http,common]
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .login-container {
    /*box-shadow: 0 0px 8px 0 rgba(0, 0, 0, 0.06), 0 1px 0px 0 rgba(0, 0, 0, 0.02);*/
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    background-clip: padding-box;
    margin: 180px auto;
    width: 350px;
    padding: 35px 35px 15px 35px;
    background: #fff;
    border: 1px solid #eaeaea;
    box-shadow: 0 0 25px #cac6c6;
  }
  .title {
    margin: 0px auto 40px auto;
    text-align: center;
    color: #505458;
  }
  .remember {
    margin: 0px 0px 35px 0px;
  }
</style>
