<template>
  <el-container>
    <el-main>
      <el-col :span="6" v-for="(item, index) in DataSource" :key="item.id">
        <el-card :body-style="{ padding: '0px' }">
          <img class="image" v-lazy="item.src">
          <div style="padding: 0px;">
            <span>{{item.name.length>16?item.name.substring(0,16)+'...':item.name}}</span>
            <div class="bottom clearfix">
              <el-button type="text" class="button" @click="showInfo(item.id)">查看详情</el-button>
              <el-button type="danger" class="button">加入购物车</el-button>
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
              })
            })
          }
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

</style>
