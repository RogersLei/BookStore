<template>
  <div class="sale">
    <div class="sale-header">
      <div v-if="listLength!==0" class="sale-header-tips">
        <h4 class="sale-header-title">昨日概况</h4>
        <el-row>
          <el-col :span="6" class="sale-header-main" :offset="3">
            <span class="sale-header-main-title">销售额</span>
            <span>{{sum}}</span>
          </el-col>
          <el-col :span="6" class="sale-header-main">
            <span class="sale-header-main-title">单笔最大额</span>
            <span>{{max}}</span>
          </el-col>
          <el-col :span="6" class="sale-header-main">
            <span class="sale-header-main-title">订单数量</span>
            <span>{{listLength}}</span>
          </el-col>
        </el-row>
      </div>
      <div class="sale-header-empytTips" v-else>
          暂无数据
      </div>
    </div>
    <div class="sale-dataset">
      <div id="myChart" :style="{width: '800px', height: '500px', margin: '0 auto' }"></div>
    </div>
  </div>
</template>

<script>
  import  http from '../../../assets/js/http'
  export default {
    name: "sales-count",
    data() {
      return {
        sum: 0,
        max: 0,
        listLength: 0,
        defaultTimeline: 30
      }
    },
    methods: {
      getYesterdayData: function () {
        //  获取昨日销售额，单笔最大数，购买最多的分类
        this.apiPost('admin/sales/yesterdaySales').then(res => {
          if(Object.prototype.toString.call(res) !== '[object Array]'){
            this.$message.warning('暂未获取到数据，请稍后重试')
            return
          }
          if(res.length >0 ){
            let priceSum = 0
            let maxPrice = res[0].Order_Price
            res.map(item => {
              priceSum += +item.Order_Price
            })
            this.sum = priceSum
            this.max = maxPrice
            this.listLength = res.length
          } else {
            this.$message.info('昨日没有销售额')
          }
        })
      },
      getData: function (timeline) {
        let obj = {
          timeline: timeline
        }
        this.apiPost('admin/sales/getSalesByTimeline',obj).then(res => {
          this.drawLine(res)
        })
      },
      drawLine: function (data) {
        let dateList = data.map(item => {
          return item.Order_EndTime.split(' ')[0]
        })
        let valueList = data.map(item => {
          return +item.Order_Price
        })
        // start 合并相同时间内的销售额
        let newValueLise = []
        let index = 0
        newValueLise[index] = valueList[index]
        for(let i=1;i<dateList.length;i++){
          if(dateList[i-1] === dateList[i]){
            newValueLise[index] = newValueLise[index] + valueList[i]
          } else {
            index++
            newValueLise[index] = valueList[i]
          }
        }
        dateList = Array.from(new Set(dateList))
        // end
        let myChart = this.$echarts.init(document.getElementById('myChart'),'light')
        myChart.setOption({
          title: { text: `最近30天` },
          tooltip: {},
          legend: {
            data:['销售额']
          },
          xAxis: {
            data:dateList
          },
          yAxis: {},
          series: [{
            name: '销售额',
            type: 'line',
            data: newValueLise,
            smooth: true,
          }]
        })
      },
      format: function (time) {
        let year = time.getFullYear()
        let month = time.getMonth()+1 < 10 ? '0' + (time.getMonth()+1) : time.getMonth()+1
        let day = time.getDate() < 10 ? '0' + time.getDate() : time.getDate()
        let hours = time.getHours() < 10 ? '0' + time.getHours() : time.getHours()
        let minutes = time.getMinutes() < 10 ? '0' + time.getMinutes() : time.getMinutes()
        let seconds = time.getSeconds() < 10 ? '0' + time.getSeconds() : time.getSeconds()
        return `${year}/${month}/${day}`
      }
    },
    mounted () {
      this.getYesterdayData()
      this.getData(this.defaultTimeline)
    },
    mixins: [http]
  }
</script>

<style scoped>
  .sale-header-tips{
    height: 130px;
  }
  .sale-header-empytTips{
    height: 130px;
    text-align: center;
    padding: 50px;
  }
  .sale-header-main{
    display: flex;
    flex-direction: column;
  }
  .sale-header-main:not(:last-child) {
    border-right: 1px solid #e7e7ed;
  }
  .sale-header-title{
    display: inline-block;
    font-weight: 400;
    font-size: 20px;
    margin-bottom: 30px;
  }
  .sale-header-main-title{
    color: #9a9a9a;
  }
</style>
