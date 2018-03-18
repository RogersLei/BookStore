<template>
  <el-container style="margin: 20px 20px 0;  border: 1px solid #dbdbdb;">
    <el-col :span="10" style="margin-top: 80px">
      <img v-lazy="DataSource.src" alt="图书图片" style="margin-left: 150px">
    </el-col>
    <el-col :span="14" style="text-align: left">
      <h2>{{DataSource.name}}</h2>
      <div style="padding: 20px 0;">
        <span style="color: #bdbdc1;">{{DataSource.des}}</span>
        <span style="margin-left: 100px; color: red; font-weight: bold">
          <span>¥</span> <span style="font-size: 24px">{{DataSource.price}}</span>
        </span>
      </div>
      <div style="padding: 20px 0;">
        <span style="color: #9f8f8d; font-size: 14px">数量：</span>
        <el-input-number size="mini" v-model="num" :min=0 :max='DataSource.stock' @change="handleChange"></el-input-number>
      </div>
      <div style="padding: 20px 0;color: #9f8f8d;">
        <span style="color: #9f8f8d; font-size: 14px">当前仅剩：</span> <span style="color: red; font-size: 24px">{{DataSource.stock}}</span> 本
      </div>
      <div class="clearfix" style="padding: 20px 0;">
        <el-button type="danger" class="button" @click="addToCart(DataSource.id, num)">加入购物车</el-button>
        <el-button type="danger" class="button" @click="Buy()">立即购买</el-button>
      </div>
    </el-col>
    <el-dialog  title="填写订单" :visible.syc="dialogVisible" @close="handleCloseDialog">
      <el-form :model="dialog" label-width="120px">
        <el-form-item label="商品名称" prop="name">
          <span style="font-size: 18px">{{dialog.name}}</span>
        </el-form-item>
        <el-form-item label="商品价格" prop="price">
          <el-col :span="12">
            <span style="color: red; font-size: 22px;">{{dialog.price}}</span>
          </el-col>
          <el-col :span="12">
            <el-input-number size="mini" v-model="num" :min=0 :max='dialog.stock' @change="ChangeNum"></el-input-number>
          </el-col>
        </el-form-item>
        <el-form-item label="总金额" prop="allPrice">
          <span style="color: red; font-size: 28px;">¥ {{dialog.num*dialog.price}}</span>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-button type="" @click="handleCloseDialog">再去看看</el-button>
          <el-button type="primary" @click="submitOrder">提交订单</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </el-container>
</template>

<script>
  import http from '../../../assets/js/http'
  import common from '../../../assets/js/common'
  import ElContainer from "element-ui/packages/container/src/main";
  export default {
    components: {ElContainer},
    name: "goods",
    data() {
      return {
        DataSource: {},
        num: 0,
        dialogVisible: false,
        dialog: {}
      }
    },
    methods: {
      loadBook() {
        let obj = this.$route.query
        this.apiPost('admin/base/findBookByID', obj).then((res)=>{
          // console.log(res)
          if(res === '' || res === null) {
            console.log('no data')
            this.$message.error('没有数据，请联系数据库管理员')
          } else {
            this.DataSource = {
              id: res[0].Book_ID,
              name: res[0].Book_Name,
              sales: res[0].Book_Sales,
              src: res[0].Book_Img,
              price: res[0].Book_Price,
              des: res[0].Book_Des.substr(0,16)+'...',
              stock: res[0].Book_Stock,
            }
          }
        })
      },
      handleChange(value) {
        this.num = value
      },
      ChangeNum(value) {
        this.dialog.num = value
      },
      Buy() {
        let user = JSON.parse(sessionStorage.getItem('user'))
        this.dialog = Object.assign({},user,this.DataSource)
        this.dialog.num = this.num
        this.dialogVisible = true
      },
      submitOrder() {
        console.log(this.dialog)
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
