<template>
  <div>
    <img src="http://img60.ddimg.cn/upload_img/00111/404/error_01.jpg" alt="">
    <p class="title">对不起，您要访问的页面暂时没有找到，</p>
    <el-button type="info" @click="goToBack">返回之前页面</el-button>
    <el-button type="info" @click="goToLogin">返回首页重新登录账号</el-button>
  </div>

</template>

<script>
  export default {
    methods: {
      goToBack () {
        this.$router.go(-1)
      },
      goToLogin () {
        console.log(this.$route)
        this.$router.push('/login')
      }
    }
  }
</script>

<style scoped>
  .title{
    font: 14px/24px "Microsoft YaHei";
    color: #888;
    padding: 40px 0 15px;
  }
</style>
