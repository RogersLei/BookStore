<template>
  <el-form :model="ruleForm2" status-icon :rules="rules2" ref="ruleForm2" label-width="120px" class="middle">
    <el-form-item label="姓名" prop="name">
      <el-input type="text" v-model="ruleForm2.name" auto-complete="off"></el-input>
    </el-form-item><el-form-item label="电话" prop="tel">
      <el-input type="tel" v-model="ruleForm2.tel" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="密码" prop="pass">
      <el-input type="password" v-model="ruleForm2.pass" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="确认密码" prop="checkPass">
      <el-input type="password" v-model="ruleForm2.checkPass" auto-complete="off"></el-input>
    </el-form-item>
    <el-form-item label="年龄" prop="age">
      <el-input v-model.number="ruleForm2.age"></el-input>
    </el-form-item>
    <el-form-item label="类别">
      <el-select v-model="ruleForm2.type" placeholder="员工级别" style="width: 380px;">
        <el-option label="店长" value="店长"></el-option>
        <el-option label="管理员" value="管理员"></el-option>
        <el-option label="前台管理员" value="前台管理员"></el-option>
      </el-select>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="submitForm('ruleForm2')">提交</el-button>
      <el-button @click="resetForm('ruleForm2')">重置</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
  import http from '../../../assets/js/http'
  export default {
    data() {
      var isValidPhone = (str) => {
        const reg = /^1[3|4|5|7|8][0-9]\d{8}$/ ;
        return reg.test(str);
      };

      var checkTel = (rule, value, callback) => {
        if (value === '') {
          return callback(new Error('电话不能为空'));
        } else if (!isValidPhone(value)) {
          return callback(new Error('请输入正确的电话号码 '))
        }
        setTimeout(() => {
          callback();
        }, 1000);
      };

      var checkAge = (rule, value, callback) => {
        if (!value) {
          return callback(new Error('年龄不能为空'));
        }
        setTimeout(() => {
          if (!Number.isInteger(value)) {
            callback(new Error('请输入数字值'));
          } else {
            if (value < 18 || value >60) {
              callback(new Error('确保年龄有效'));
            } else {
              callback();
            }
          }
        }, 1000);
      };
      var validatePass = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'));
        } else {
          if (this.ruleForm2.checkPass !== '') {
            this.$refs.ruleForm2.validateField('checkPass');
          }
          callback();
        }
      };
      var validatePass2 = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'));
        } else if (value !== this.ruleForm2.pass) {
          callback(new Error('两次输入密码不一致!'));
        } else {
          callback();
        }
      };
      return {
        ruleForm2: {
          name: '',
          tel: '',
          pass: '',
          checkPass: '',
          age: '',
          type: ''
        },
        rules2: {
          tel: [
            { validator: checkTel, trigger: 'blur'},
          ],
          pass: [
            { validator: validatePass, trigger: 'blur' }
          ],
          checkPass: [
            { validator: validatePass2, trigger: 'blur' }
          ],
          age: [
            { validator: checkAge, trigger: 'blur' }
          ]
        }
      };
    },
    methods: {

      submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            let obj = {
              name: this.ruleForm2.name,
              pass: this.ruleForm2.pass,
              tel: this.ruleForm2.tel,
              age: this.ruleForm2.age,
              type: this.ruleForm2.type
            }
            this.apiPost('admin/base/insertEmp',obj).then((res) => {
              if(res.code == 200){
                this.$message({
                  message: '信息添加成功',
                  type: 'success'
                })
                this.$router.go('userL')
              }
              else {
                this.$message.error(res.msg)
              }
            })
          } else {
            this.$message.error('添加失败，请确认信息是否合法');
            return false;
          }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
    },
    mixins: [http]
  }
</script>

<style scoped>
  .middle{
    width: 500px;
    margin: 0 auto;
  }
</style>
