<template>
  <el-container class="main">
    <el-col :span="6" >
      <img :src="user.src" alt="" style="height: 130px;width: 130px;">
      <p>{{user.name}}</p>
      <el-menu
        :default-active="active"
        class="el-menu-vertical-demo"
        text-color="#c1c1c1"
        router
        style="border:0"
      >
        <el-menu-item index="/index/user/info">
          <i class="el-icon-info"></i>
          <span slot="title">个人信息</span>
        </el-menu-item>
        <el-menu-item index="/index/user/cart">
          <i class="el-icon-goods"></i>
          <span slot="title">购物清单</span>
        </el-menu-item>
        <el-menu-item index="/index/user/address">
          <i class="el-icon-document"></i>
          <span slot="title">配送地址</span>
        </el-menu-item>
        <el-menu-item index="/index/user/order">
          <i class="el-icon-tickets"></i>
          <span slot="title">订单信息</span>
        </el-menu-item>
      </el-menu>
    </el-col>
    <el-col :span="18" >
      <el-col :span="24">
        <strong class="title" style="float: left; padding-left: 100px; color: #c1c1c1">{{$route.name}}</strong>
      </el-col>
      <el-col :span="24" style="border: 1px solid #c1c1c1">
        <transition name="fade" mode="out-in">
          <router-view name="UserAll" @change="changeUser"></router-view>
        </transition>
      </el-col>

    </el-col>

  </el-container>
</template>

<script>
  export default {
    name: "user",
    data(){
      return {
        // active: {
        //   'info': '/index/user/info',
        //   'cart': '/index/user/cart',
        //   'address': '/index/user/address',
        //   'order': '/index/user/order'
        // }[this.$route.params.id]
        // 传:id是点击购物车／个人中心时 params.id不变且 router-view不能渲染
        active: this.$route.path,
        user: {},
      }
    },
    methods: {
      changeUser (value) {
        this.user = value
        this.$emit('change',value)
      }
    },
    mounted() {
      this.user = JSON.parse(sessionStorage.getItem('user'))
    },
    mixins: []
  }
</script>

<style scoped lang="scss">
  .main{
    min-height: 500px;
    margin-top: 50px;
  }
</style>
