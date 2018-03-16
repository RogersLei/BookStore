<template>
  <el-container class="container">
    <el-col :span="24" class="hh">
      <el-col :span="6" :offset="18" style="margin-top: 8px;">
        <el-button type="primary" size="medium" >登陆</el-button>
        <el-button type="primary" size="medium" >注册</el-button>
      </el-col>
    </el-col>
    <el-col :span="24" class="hm" style="margin-top: 10px">
      <el-col :span="6">
        <router-link to="/index"><img src="../../assets/1.jpg" alt="xxx" height="100" width="200"></router-link>
      </el-col>
      <el-col :span="12">
        <el-autocomplete class="inline-input" v-model="searchBook" :fetch-suggestions="queryBook"
                         :trigger-on-focus="false" style="width: 500px"
                         placeholder="请输入内容" suffix-icon="el-icon-search" @select="handleSelect">
        </el-autocomplete>
      </el-col>
      <el-col :span="6">
        <router-link to="/index/all"><el-button type="" style="margin-right: 0">全部商品</el-button></router-link>

        <router-link to="/index/user/cart"><el-button type="danger" icon="el-icon-goods" style="margin-left: 0">购物车 <span style="margin-left: 3px">{{GoodsNum}}</span></el-button></router-link>

        <router-link to="/index/user/info"><el-button type="" style="margin-left: 0" >个人中心</el-button></router-link>
      </el-col>
    </el-col>

    <router-view name="Home"></router-view>

    <el-footer>aaa</el-footer>
  </el-container>
</template>

<script>
  import common from '../../assets/js/common'
  import http from '../../assets/js/http'
    export default {
      name: "home",
      data() {
          return {
            books: [],
            searchBook: '',
            GoodsNum: 3,
            activeName: 'second',
        // tags: []
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
        }
      },
      mounted() {
        // this.loadBook()
        // console.log(this.$route.params)
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
      height: 30px;
      line-height: 30px;
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
</style>
