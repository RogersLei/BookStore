<template>
  <el-container>
    <el-row style="margin: 20px 20px 0;  border: 1px solid #dbdbdb;">
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
    </el-row>
    <el-footer v-if="user">
      <div class="lines" v-if="recommendArr.length>0">
        <span>猜你喜欢</span>
      </div>
      <el-col :span="6" v-for="(item, index) in recommendArr" :key="item.Book_ID">
        <el-card :body-style="{ padding: '0px' }" class="card-item">
          <img class="image" v-lazy="item.Book_Img">
          <div style="padding: 0px;">
            <span>{{item.Book_Name.length>16?item.Book_Name.substring(0,16)+'...':item.Book_Name}}</span>
            <div class="bottom clearfix">
              <el-button type="text" class="button" @click="showInfo(item.Book_ID)">查看详情</el-button>
              <el-button type="text" class="button" @click="addToCart(item.Book_ID)" v-if="item.Book_Stock !==0">加入购物车</el-button>
              <el-button type="text" v-else disabled>已售完</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
    </el-footer>
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
        <el-form-item label="配送信息" prop="name">
          <el-select v-model="curAddress" placeholder="请选择" style="width: 400px" @change="click">
            <el-option
              v-for="(item,index) in address"
              :key="index"
              :label="(item.name+item.tel+item.address)"
              :value="(item.name+item.tel+item.address)">
            </el-option>
          </el-select>
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
  import ElFooter from "element-ui/packages/footer/src/main";
  export default {
    components: {
      ElFooter,
      ElContainer},
    name: "goods",
    data() {
      return {
        DataSource: {},
        num: 0,
        dialogVisible: false,
        dialog: {},
        address: [],
        curAddress: '',
        user: {},
        recommendArr: []
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
        if(this.num === 0){
          this.$message.error('请先确认数量')
          return
        }
        let user = JSON.parse(sessionStorage.getItem('user'))
        if(!user){
          this.$message.error('请在右上角先登陆')
          return
        }
        this.apiPost('font/base/findAddress',{account: user.account}).then((res)=>{
          if(res !== null){
            if(res.code !==0) {
              res.forEach((item) => {
                this.address.push(item)
              })
            }
          } else {
            this.$message.error('数据出错，请联系后台人员查看数据库')
          }
        })
        this.dialog = Object.assign({},user,this.DataSource)
        this.dialog.num = this.num
        this.dialogVisible = true
      },
      click (value) {
        this.curAddress = value
      },
      submitOrder() {
        if( this.curAddress ===''){
          this.$message.error('请选择配送地址')
          return
        }
        let obj = {
          account: this.dialog.account,
          address: this.curAddress,
          order: [{
            id: this.dialog.id,
            num: this.dialog.num,
            price: this.dialog.price*this.dialog.num
          }],
          tprice: this.dialog.price*this.dialog.num
        }
        let Now = new Date()
        let Y = Now.getFullYear()
        let M = '0' + (Now.getMonth()+1)
        let D = '0' + (Now.getDate())
        let h = '0' + (Now.getHours())
        let m = '0' + (Now.getMinutes())
        let s = '0' + (Now.getSeconds())
        let date = Y +'-' + M.substring(M.length-2, M.length) + '-'+D.substring(D.length-2, D.length) + ' '
          + h.substring(h.length-2, h.length) + ':' + m.substring(m.length-2, m.length) + ':'
          + s.substring(s.length-2, s.length)
        obj.time = date
        this.apiPost('font/base/createOrder',obj).then((res)=>{
          console.log(res)
          if(res.code === 200) {
            this.$message.success('提交订单成功')
            this.$router.push('/index/user/order')
          } else {
            this.$message.error('购买失败')
          }
        })
      },
      recommend(history) {
        let obj = {
          books: history
        }
        this.apiPost('admin/base/recommend',obj).then(res => {
          if(res.code === 200){
            let recommend = []
            if (res.data.length > 12) {
              let indexArr = []
              for(let i=0;indexArr.length<12;i++){
                indexArr.push(Math.floor(Math.random()*res.data.length))
                indexArr = Array.from(new Set(indexArr))
              }
              for(let j=0;j<indexArr.length;j++){
                console.log(indexArr)
                console.log(indexArr[j])
                recommend.push(res.data[indexArr[j]])
              }
            } else {
              for(let i=0;i<res.data.length;i++){
                recommend.push(res.data[i])
              }
            }
            for(let i=0;i<recommend.length;i++){
              this.recommendArr.push(recommend[i])
            }
            // this.recommendArr = recommend
            console.log(this.recommendArr)
          } else {
            this.$message.error(res.msg)
          }
        })
      }
    },
    mounted(){
      this.loadBook()
      this.user = JSON.parse(sessionStorage.getItem('user'))
      if(this.user && JSON.parse(sessionStorage.getItem('history'))){
        let history = JSON.parse(sessionStorage.getItem('history'))
        this.recommend(history)
      }
    },
    mixins: [http,common]
  }
</script>

<style scoped>
  .card-item:hover{
    transform: translateY(-3px);
    box-shadow: 1px 1px 20px #999;
  }
  .lines{
    padding: 0 20px 0;
    margin: 20px 0;
    line-height: 2px;
    border-left: 580px solid rgb(58,59,72);
    border-right: 580px solid rgb(58,59,72);
    text-align: center;
  }
</style>
