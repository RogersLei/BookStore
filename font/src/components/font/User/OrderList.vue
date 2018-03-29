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
        prop="Order_Status"
        label="订单状态"
        align="center"
        width="180">
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
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
  export default {
    name: "order-list",
    props:['orders'],
    methods: {
      handlePay (index, row) {
        console.log(index,row)
      },
      handleDelete (index, row) {
        console.log(index,row)
      },
      handleCall (index, row) {
        console.log(index,row)
      },
      handleSearch (index, row) {
        console.log(index,row)
      },
    }
  }
</script>

<style scoped>

</style>
