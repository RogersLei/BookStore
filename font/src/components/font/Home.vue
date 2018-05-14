<template>
  <el-container class="container">
    <el-col :span="24" class="hh">
      <el-col :span="6" :offset="18" style="margin-top: 8px;" v-if="!isLogin">
        <a @click="handleLogin">登陆</a>
        <a @click="handleReg">注册</a>
      </el-col>
      <el-col :span="4" :offset="20" style="margin-top: 8px; color: #c1c1c1; font-size: 12px" v-else>
        <span>{{user.name}}</span>
        <a href="javascript:void(0);" @click="exitLogin">退出</a>
      </el-col>
    </el-col>
    <el-col :span="24" class="hm" style="margin-top: 10px">
      <el-col :span="6">
        <router-link to="/index"><img src="../../assets/1.jpg" alt="xxx" height="100" width="200"></router-link>
      </el-col>
      <el-col :span="6" :offset="12">
        <router-link to="/index/all"><el-button type="" style="margin-right: 0">全部商品</el-button></router-link>

        <router-link to="/index/user/cart"><el-button type="danger" icon="el-icon-goods" style="margin-left: 0">购物车 <span style="margin-left: 3px" v-if="isLogin"></span></el-button></router-link>

        <router-link to="/index/user/info"><el-button type="" style="margin-left: 0" >个人中心</el-button></router-link>
      </el-col>
    </el-col>

    <router-view name="Home" @change="changeUser"></router-view>

    <el-footer></el-footer>
    <el-dialog  title="登陆" :visible.syc="dialogVisibleLog" @close="resetLogin('login')">
      <el-form :model="login" ref="login" label-width="120px">
        <el-form-item label="账户名" prop="account">
          <el-input type="text" v-model="login.account"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="pass">
          <el-input type="password" v-model="login.pass"></el-input>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-button type="info" @click="resetLogin('login')">取消</el-button>
          <el-button type="primary" @click="submitLogin()">登陆</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog  title="注册" :visible.syc="dialogVisibleReg" @close="resetReg('register')">
      <el-form :model="register" :rules="rulesReg" ref="register" label-width="120px">
        <el-form-item label="昵称" prop="name">
          <el-input type="text" v-model="register.name" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="账号" prop="account">
          <el-input type="text" v-model="register.account" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="pass">
          <el-input type="password" v-model="register.pass" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item label="确认密码" prop="checkPass">
          <el-input type="password" v-model="register.checkPass" auto-complete="off"></el-input>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-button type="info" @click="resetReg('register')">取消</el-button>
          <el-button type="primary" @click="submitReg('register')">注册</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </el-container>
</template>

<script>
  import common from '../../assets/js/common'
  import http from '../../assets/js/http'
    export default {
      name: "home",
      data() {
        var validatePass = (rule, value, callback) => {
          if (value === '') {
            callback(new Error('请输入密码'));
          } else {
            if (this.register.checkPass !== '') {
              this.$refs.register.validateField('checkPass');
            }
            callback();
          }
        };
        var validatePass2 = (rule, value, callback) => {
          if (value === '') {
            callback(new Error('请再次输入密码'));
          } else if (value !== this.register.pass) {
            callback(new Error('两次输入密码不一致!'));
          } else {
            callback();
          }
        };
        return {
          books: [],
          searchBook: '',
          activeName: 'second',
          isLogin: false,
          dialogVisibleLog: false,
          dialogVisibleReg: false,
          login: {
            account: '',
            pass: ''
          },
          register: {
            name: '',
            account: '',
            pass: '',
            checkPass: ''
          },
          rulesReg: {
            name: [
              { trigger: 'blur', required: true, message: '请输入昵称' }
            ],
            account: [
              { trigger: 'blur', required: true, message: '请输入账户名' }
            ],
            pass: [
              { validator: validatePass, trigger: 'blur', required: true }
            ],
            checkPass: [
              { validator: validatePass2, trigger: 'blur', required: true }
            ],
          },
          user: {},
        }
      },
      methods: {
        loadBook () {
          this.apiPost('admin/base/findBook').then((res)=> {
            if (res == '' || res == null) {
              console.log('data error')
            } else {
              res.forEach((item, index) => {
                this.books[index] = {
                  id: item.Book_ID,
                  value: item.Book_Name,
                  num: item.Book_Stock,
                  tag: item.Book_Type,
                  price: item.Book_Price
                }
               // this.tags[index] = item.Book_Type
              })
              //this.tags = Array.from(new Set(this.tags))
              //console.log(this.books)
            }
          })
        },
        handleSelect (item) {
          console.log(item)
        },
        handleClick(tab, event) {
          console.log(tab, event);
        },
        addToCart(id) {
          this.GoodsNum++
        },

        handleLogin() {
          this.dialogVisibleLog = true
        },
        resetLogin() {
          this.login = {
            account: '',
            pass: ''
          }
          this.dialogVisibleLog = false
        },
        submitLogin() {
          let user = {
            account: this.login.account,
            pass: this.login.pass
          }
          this.apiPost('font/base/login',user).then((res) => {
            if(res.code == 200){
              user.name = res.data.User_Name
              user.src = res.data.User_Img
              user.balance = res.data.User_Balance
              delete user.pass
              sessionStorage.setItem('user', JSON.stringify(user));
              this.dialogVisibleLog = false
              this.isLogin = true
              this.user = {
                name: user.name,
              }
              this.$router.go(this.$route.fullPath)
            } else {
              this.$message.error(res.msg)
            }
          })
        },

        handleReg() {
          this.dialogVisibleReg = true
        },
        resetReg(form) {
          this.$refs[form].resetFields();
          this.dialogVisibleReg = false
        },
        submitReg(form) {
          this.$refs[form].validate((valid) => {
            if (valid) {
              let user = {
                nickname: this.register.name,
                account: this.register.account,
                pwd: this.register.pass
              }
              this.apiPost('font/base/register', user).then((res)=>{
                if(res.code == 200){
                  this.$message({
                    message: '注册成功',
                    type: 'success'
                  })
                  this.dialogVisibleReg = false
                  this.dialogVisibleLog = true
                }
                else {
                  this.$message.error(res.msg)
                }
              })
            } else {
              this.$message.error('添加失败，请确认信息是否合法');
              return false;
            }
          })

        },

        exitLogin() {
          var _this = this;
          this.$confirm('确认退出吗?', '提示', {
            //type: 'warning'
          }).then(() => {
            sessionStorage.removeItem('user');
            this.user = {}
            this.isLogin = false
            _this.$router.push('/index');
          }).catch(() => {

          });
        },
        changeUser (value) {
          this.user = value
        }

      },
      mounted() {
        let user = JSON.parse(sessionStorage.getItem('user'))
        if(user){
          this.isLogin = true
          this.user = user
          // console.log(this.user)
        } else {
          this.isLogin = false
        }

      },
      mixins: [http, common]

    }
</script>

<style scoped lang="scss">
  .container{
    min-width: 1400px;
    position: absolute;
    top: 0px;
    bottom: 0px;
    width: 100%;
    .hh {
      height: 20px;
      line-height: 20px;
    }
    .hm {
      height: 100px;
      line-height: 100px;
    }
  }
  .el-carousel__item h3 {
    color: #475669;
    font-size: 14px;
    opacity: 0.75;
    line-height: 150px;
    margin: 0;
  }

  .el-carousel__item:nth-child(2n) {
    background-color: #99a9bf;
  }

  .el-carousel__item:nth-child(2n+1) {
    background-color: #d3dce6;
  }

  a{
    color: #c1c1c1;
    text-decoration: none;
  }
</style>
