<template>
  <el-container class="main">
    <el-col :span="6">
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
    <el-col :span="18">
      <el-col :span="24">
        <strong class="title" style="float: left; padding-left: 100px; color: #c1c1c1">{{$route.name}}</strong>
      </el-col>
      <el-col :span="24" style="border: 1px solid #c1c1c1; margin-right: 20px;">
        <transition name="fade" mode="out-in">
          <router-view name="UserAll" @change="changeUser" :carts="carts" :address="address" :orders="orders"
                       :key="$route.fullPath"></router-view>
        </transition>
      </el-col>

    </el-col>

  </el-container>
</template>

<script>
  import http from '../../../assets/js/http'

  export default {
    name: "user",
    data() {
      return {
        active: this.$route.path,
        user: {},
        carts: [],
        address: [],
        orders: []
      }
    },
    methods: {
      changeUser(value) {
        this.user = value
        this.$emit('change', value)
      },
      changeRoute() {
        this.active = this.$route.path
        if (this.$route.path === '/index/user/cart') {
          this.getCarts()
        } else if (this.$route.path === '/index/user/address') {
          this.getAddress()
        } else if (this.$route.path === '/index/user/order') {
          this.getOrder()
        }
      },
      getCarts() {
        let obj = {
          account: this.user.account
        }
        this.apiPost('font/base/findCarts', obj).then((res) => {
          if (res !== null) {
            if (res.code !== 0) {
                this.carts = res
            } else {
              this.$message.error('数据出错，请联系后台人员查看数据库')
            }
          }
        })
      },
      getAddress() {
        let obj = {
          account: this.user.account
        }
        this.apiPost('font/base/findAddress', obj).then((res) => {
          if (res !== null) {
            if (res.code !== 0) {
                this.address = res
            }
          } else {
            this.$message.error('数据出错，请联系后台人员查看数据库')
          }
        })
      },
      getOrder() {
        let obj = {
          account: this.user.account
        }
        this.apiPost('font/base/findOrder', obj).then((res) => {
          if (res !== null) {
            if (res.code !== 0) {
              res.map((item) => {
                item.Order_Price = 0
                item.Order_Books = JSON.parse(item.Order_Books)
                item.Order_Book = ''
                item.Order_Books.forEach((item_book) => {
                  let ID = {id: item_book.id}
                  this.apiPost('admin/base/findBookByID', ID).then((res) => {
                    item_book.name = res[0].Book_Name
                  }).then(() => {
                    item.Order_Book += `书名:${item_book.name} 数量:${item_book.num} 价格:${item_book.price} `
                    item.Order_Price += item_book.price
                  })
                })
                return item
              })
              this.orders = res
            }
          } else {
            this.$message.error('数据出错，请联系后台人员查看数据库')
          }
        })
      }
    },
    watch: {
      "$route": "changeRoute",
    },
    mounted() {
      this.user = JSON.parse(sessionStorage.getItem('user'))
      if(this.$route.path === '/index/user/cart'){
        this.getCarts()
      } else if(this.$route.path === '/index/user/address'){
        this.getAddress()
      } else if(this.$route.path === '/index/user/order'){
        this.getOrder()
      }
    },
    mixins: [http]
  }
</script>

<style scoped lang="scss">
  .main {
    min-height: 500px;
    margin-top: 50px;
  }
</style>
