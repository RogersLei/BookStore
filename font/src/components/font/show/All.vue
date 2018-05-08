<template>
  <el-container>
    <el-header>
      <el-col :span="4" :offset="10">
        <el-input
          placeholder="搜索书籍名称"
          v-model="name"
          clearable>
        </el-input>
      </el-col>
      <el-col :span="3" :offset="1">
        <el-button type="primary" @click="search()">搜索</el-button>
      </el-col>
    </el-header>
    <el-main>
      <el-col :span="6" v-for="(item, index) in DataSource" :key="item.id">
        <el-card :body-style="{ padding: '0px' }" class="card-item">
          <img class="image" v-lazy="item.src">
          <div style="padding: 0px;">
            <span>{{item.name.length>16?item.name.substring(0,16)+'...':item.name}}</span>
            <div class="bottom clearfix">
              <el-button type="text" class="button" @click="showInfo(item.id)">查看详情</el-button>
              <el-button type="text" class="button" @click="addToCart(item.id)" v-if="item.stock !==0">加入购物车</el-button>
              <el-button type="text" v-else disabled>已售完</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
    </el-main>
  </el-container>

</template>

<script>
  import http from '../../../assets/js/http'
  import common from '../../../assets/js/common'
  export default {
    name: "all",
    data() {
      return {
        name: '',
        DataSource: [],
      }
    },
    methods: {
      loadBook() {
        this.apiPost('admin/base/findBook').then((res)=>{
          if(res === '' || res === null) {
            console.log('no data')
            this.$message.error('没有数据，请联系数据库管理员')
          } else {
            res.forEach((item, index) => {
              this.DataSource.push({
                id: item.Book_ID,
                name: item.Book_Name,
                sales: item.Book_Sales,
                src: item.Book_Img,
                price: item.Book_Price,
                stock: item.Book_Stock
              })
            })
          }
        })
      },
      search() {
        let obj = {
          name: this.name
        }
        this.apiPost('admin/base/findBookByName',obj).then(res => {
          this.DataSource = []
          res.data.forEach(item => {
            this.DataSource.push({
              id: item.Book_ID,
              name: item.Book_Name,
              sales: item.Book_Sales,
              src: item.Book_Img,
              price: item.Book_Price,
              stock: item.Book_Stock
            })
          })
        })
      }
    },
    mounted(){
      this.loadBook()
    },
    mixins: [http,common]
  }
</script>

<style scoped>
  .card-item:hover{
    transform: translateY(-3px);
    box-shadow: 1px 1px 20px #999;
  }
</style>
