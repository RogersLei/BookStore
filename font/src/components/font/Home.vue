<template>
  <el-container class="container">
    <el-col :span="24" class="hh">
      <el-col :span="6" :offset="18">
        <el-button type="text" size="medium">登陆</el-button>
        <el-button type="text" size="medium">注册</el-button>
      </el-col>
    </el-col>
    <el-col :span="24" class="hm" style="margin-top: 10px">
      <el-col :span="6">
        <router-link to="/index/aaa"><img src="../../assets/1.jpg" alt="xxx" height="100" width="200"></router-link>
      </el-col>
      <el-col :span="12">
        <el-autocomplete class="inline-input" v-model="searchBook" :fetch-suggestions="queryBook"
                         :trigger-on-focus="false" style="width: 500px"
                         placeholder="请输入内容" suffix-icon="el-icon-search" @select="handleSelect">
        </el-autocomplete>
      </el-col>
      <el-col :span="6">
        <el-button type="danger" icon="el-icon-goods">购物车 {{GoodsNum}}</el-button>
        <el-button type="" style="margin-left: 0">我的订单</el-button>
      </el-col>
    </el-col>

    <el-main>
      <!--轮播图-->
      <el-carousel trigger="click" height="250px" type="card">
        <el-carousel-item v-for="item in imgs" :key="item">
          <!--{{item}}-->
          <img src="../../assets/banner/banner1.jpg" alt="">
          <!--<img :src="item.imgUrl" alt="">-->
        </el-carousel-item>
      </el-carousel>
      <!--首页显示-->
      <el-col :span="4" v-for="(o, index) in 5" :key="o" :offset="index > 0 ? 1 : 0">
        <!--190*315-->
        <el-card :body-style="{ padding: '0px' }">
          <img src="../../assets/banner/banner1.jpg" class="image">
          <div style="padding: 0px;">
            <span>好吃的汉堡</span>
            <div class="bottom clearfix">
              <time class="time">{{1}}</time>
              <el-button type="text" class="button">操作按钮</el-button>
            </div>
          </div>
        </el-card>
        <el-card :body-style="{ padding: '0px' }">
          <img src="../../assets/banner/banner1.jpg" class="image">
          <div style="padding: 0px;">
            <span>好吃的汉堡</span>
            <div class="bottom clearfix">
              <time class="time">{{1}}</time>
              <el-button type="text" class="button">操作按钮</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
      <!--店长推荐-->
    </el-main>
    <el-footer></el-footer>
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
            GoodsNum: 0,
            activeName: 'second',
            imgs: [
              {imgUrl: '../../assets/banner/banner1.jpg'},
              {imgUrl: '../../assets/banner/banner2.jpg'},
              {imgUrl: '../../assets/banner/banner3.jpg'},
              {imgUrl: '../../assets/banner/banner4.jpg'}
            ],
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
        }
      },
      mounted() {
        this.loadBook()
      },
      mixins: [http, common]

    }
</script>

<style scoped lang="scss">
  .container{
    min-width: 1250px;
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
