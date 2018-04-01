<template>
  <div style="height: 562px;">
    <el-table
      ref="orderTable"
      :data="orders"
      max-height="522"
      tooltip-effect="dark"
      style="width: 100%"
      show-summary
    >
      <el-table-column
        prop="Order_StartTime"
        label="生成订单时间"
        align="center"
        width="180"
        >
      </el-table-column>
      <el-table-column
        prop="Order_Book"
        label="订单内容"
        align="center"
        show-overflow-tooltip
      >
      </el-table-column>
      <el-table-column
        prop="Order_Address"
        label="订单地址"
        align="center"
        show-overflow-tooltip
      >
      </el-table-column>
      <el-table-column
        prop="Order_Price"
        label="订单总价"
        align="center"
        width="120">
      </el-table-column>
      <el-table-column
        prop="Order_Status"
        label="订单状态"
        align="center"
        :filters="status"
        :filter-method="filterHandler"
        width="120">
      </el-table-column>
      <el-table-column
        prop=""
        label="操作"
        align="center"
        width="180"
      >
        <template slot-scope="scope" >
          <div v-if="scope.row.Order_Status === '待付款'">
            <el-button size="mini" type="primary" @click="handlePay(scope.$index, scope.row)">付款</el-button>
            <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">取消</el-button>
          </div>
          <div v-else-if="scope.row.Order_Status === '待发货'">
            <el-button @click="handleCall(scope.$index, scope.row)">联系卖家</el-button>
          </div>
          <div v-else-if="scope.row.Order_Status === '待收货'">
            <el-button type="success" @click="handleSearch(scope.$index, scope.row)">查看物流</el-button>
          </div>
          <div v-else-if="scope.row.Order_Status === '已完成'">
            <span>{{scope.row.Order_EndTime}}</span>
          </div>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
  import http from '../../../assets/js/http'
  export default {
    name: "order-list",
    data () {
      return {
        status: [
          {
            text: '待付款',
            value: '待付款'
          },
          {
            text: '待发货',
            value: '待发货'
          },
          {
            text: '待收货',
            value: '待收货'
          },
          {
            text: '已完成',
            value: '已完成'
          }
        ]
      }
    },
    props:['orders'],
    methods: {
      handlePay (index, row) {
        this.$confirm('你确认要进行付款么', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          let books = []
          row.Order_Books.forEach((item) => {
            books.push(item)
          })
          let obj = {
            id: row.Order_ID,
            books: books,
            price: row.Order_Price
          }

          this.apiPost('font/pay/Pay', obj).then((res) => {
            if (res.code === 200) {
              this.$message.success('付款成功')
              this.$router.go(this.$route.path)
            } else {
              this.$message.error(res.msg)
            }
          })
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消付款'
          })
        })
      },
      handleDelete (index, row) {
        this.$confirm('此操作将永久删除该行数据, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.apiPost('font/base/deleteOrderByID', {id: row.Order_ID}).then((res) => {
            if (res.code == 200) {
              this.$message.success('删除成功')
              this.$router.go(this.$route.path)
            }
          })
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消删除'
          })
        })
      },
      handleCall (index, row) {
        this.$message.success('请联系793574772@qq.com/15319990235')
      },
      handleSearch (index, row) {
        this.apiPost('font/base/seachOrderByID', { id: row.Order_ID }).then((res)=>{
          if(res.code !== 0){
            this.$message.success(`您的快递单号为: ${res.Order_Number}`)
          }
        })
      },
      filterHandler(value, row) {
        return row['Order_Status'] === value;
      }
    },
    mixins: [http]
  }
</script>

<style scoped>

</style>
