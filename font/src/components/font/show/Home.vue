<template>
  <el-container v-loading.fullscreen.lock="loading">
    <el-main>
      <!--轮播图-->
      <el-carousel trigger="click" height="250px" type="card">
        <el-carousel-item v-for="item in banners" :key="item.imgUrl">
          <!--{{item}}-->
          <img src="../../../assets/banner/banner1.jpg" alt="">
          <!--<img :src="item.imgUrl" alt="">-->
        </el-carousel-item>
      </el-carousel>
      <!--首页显示-->
      <el-col :span="8" v-for="(item, index) in DataSource.slice(0,3)" :key="item.id">
        <!--190*315-->
        <el-card :body-style="{ padding: '0px' }">
          <img :src="item.src" class="image">
          <div style="padding: 0px;">
            <span>{{item.name.length>16?item.name.substring(0,16)+'...':item.name}}</span>
            <div class="bottom clearfix">
              <el-button type="primary" class="button" @click="showInfo(item.id)">查看详情</el-button>
              <el-button type="danger" class="button">加入购物车</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
      <el-col :span="8" v-for="(item, index) in DataSource.slice(3,6)" :key="item.id">
        <el-card :body-style="{ padding: '0px' }">
          <img :src="item.src" class="image">
          <div style="padding: 0px;">
            <span>{{item.name.length>16?item.name.substring(0,16)+'...':item.name}}</span>
            <div class="bottom clearfix">
              <el-button type="primary" class="button" @click="showInfo(item.id)">查看详情</el-button>
              <el-button type="danger" class="button">加入购物车</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
      <el-col :span="8" v-for="(item, index) in DataSource.slice(6,9)" :key="item.id">
        <el-card :body-style="{ padding: '0px' }">
          <img :src="item.src" class="image">
          <div style="padding: 0px;">
            <span>{{item.name.length>16?item.name.substring(0,16)+'...':item.name}}</span>
            <div class="bottom clearfix">
              <el-button type="primary" class="button" @click="showInfo(item.id)">查看详情</el-button>
              <el-button type="danger" class="button" @click="addToCart(item.id)">加入购物车</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
      <!--店长推荐-->
    </el-main>
  </el-container>
</template>

<script>
  import http from '../../../assets/js/http'
  import common from '../../../assets/js/common'
  export default {
    name: "home",
    data() {
      return {
        loading: false,
        DataSource: [],
        banners: [
          {imgUrl: '../../assets/banner/banner1.jpg'},
          {imgUrl: '../../assets/banner/banner2.jpg'},
          {imgUrl: '../../assets/banner/banner3.jpg'},
          {imgUrl: '../../assets/banner/banner4.jpg'}
        ],
      }
    },
    methods: {
      loadBook() {
        this.loading = true
        this.apiPost('admin/sales/salesTop10').then((res) => {
          if(res === '' || res === null) {
            console.log('no data')
            this.loading = false
            this.$message.error('没有数据，请联系数据库管理员')
          } else {

            res.forEach((item, index) => {
              this.DataSource[index] = {
                id: item.Book_ID,
                name: item.Book_Name,
                sales: item.Book_Sales,
                src: item.Book_Img,
                price: item.Book_Price,
              }
            })
            this.loading = false
          }
        })
      },
      loadBanner() {

      },
    },
    mounted() {
      this.loadBook()
      this.loadBanner()
    },
    mixins: [http,common]
  }
</script>

<style scoped>

</style>
