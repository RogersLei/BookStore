<template>
  <div style="height: 562px;">
    <el-table
      ref="cartTable"
      :data="message"
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
      <el-button type="danger" class="button" @click="Buy()">立即购买</el-button>
    </div>
  </div>
</template>

<script>
  export default {
    name: "cart",
    data() {
      return {
      }
    },
    props:['message'],
    methods: {
    }

  }
</script>

<style scoped>

</style>
