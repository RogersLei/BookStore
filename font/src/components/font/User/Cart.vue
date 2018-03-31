<template>
  <div style="height: 562px;">
    <el-table
      ref="cartTable"
      :data="carts"
      max-height="522"
      tooltip-effect="dark"
      style="width: 100%"
      show-summary
    >
      <el-table-column
        type="selection"
        align="center"
        width="120">
      </el-table-column>
      <el-table-column
        prop="name"
        label="书名"
        align="center"
        show-overflow-tooltip>
      </el-table-column>
      <el-table-column
        prop="num"
        label="数量"
        align="center"
        width="180">
      </el-table-column>
      <el-table-column
        prop="tprice"
        label="总价"
        align="center"
        width="180">
      </el-table-column>
    </el-table>
    <div style="float: right">
      <el-button type="danger" class="button" @click="buy">立即购买</el-button>
    </div>
    <el-dialog  title="填写订单" :visible.syc="dialogVisible" @close="handleCloseDialog">
      <el-form label-width="120px">
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
  </div>
</template>

<script>
  import common from '../../../assets/js/common'
  export default {
    name: "cart",
    data() {
      return {
        account: '',
        address: [],
        dialogVisible: false,
        curAddress: ''
      }
    },
    props:['carts'],
    methods: {
      buy () {
        this.dialogVisible = true
        this.account = JSON.parse(sessionStorage.getItem('user')).account
        this.apiPost('font/base/findAddress',{account: this.account}).then((res)=>{
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
      },
      click (value) {
        this.curAddress = value
      },
      submitOrder () {
        let tprice = 0
        let obj = {
          account: this.account,
          address: this.curAddress,
          order: this.$refs.cartTable.data.map((item)=>{
            item.price = +item.tprice
            tprice += item.price
            obj = {
              id: item.id,
              num: item.num,
              price: item.price
            }
            return obj
          }),
          tprice: tprice
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
        console.log(obj)
        this.apiPost('font/base/createOrder',obj).then((res)=>{
          if(res.code === 200) {
            this.$message.success('提交订单成功')
            this.apiPost('font/base/deleteCart', { account: this.account}).then((res)=>{
              if(res.code === 200 ){
                this.$router.go('/index/user/order')
              }
            })
          } else {
            this.$message.error('购买失败')
          }
        })
      }

    },
    mixins: [common]

  }
</script>

<style scoped>

</style>
