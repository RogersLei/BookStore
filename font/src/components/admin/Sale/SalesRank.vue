<template>
  <div>
    <div id="myChart" :style="{width: '800px', height: '500px', margin: '0 auto' }"></div>
  </div>
</template>

<script>
  import http from '../../../assets/js/http'
  export default {
    name: "sales-rank",
    data() {
      return {
        DataSource: [
          {
            type: "成功/励志",
            sales: 5,
          },
          {
            type: "小说",
            sales: 20,
          },
          {
            type: "中小学教辅",
            sales: 36,
          },
          {
            type: "童书",
            sales: 50,
          },
          {
            type: "育儿/早教",
            sales: 10,
          },
          {
            type: "动漫/幽默",
            sales: 20,
          },
        ],
        typeData: [],
        salesData: [],
        loading: 0,
      }
    },
    methods: {
      getTypeData(){
        // this.apiPost('admin/sales/salesTop5').then((res) => {
        //   if(res.code === 200) {
        //     this.DataSource = res.data
        //     this.dealData(this.DataSource)
        //   }
        // })
      },
      dealData(data) {
        this.typeData = data.map((item) => {
          return item.type
        })
        this.salesData = data.map((item) => {
          return item.sales
        })
        this.drawLine()
        this.loading = new Date()
        console.log(this.loading)
      },
      drawLine() {
        let myChart = this.$echarts.init(document.getElementById('myChart'))
        myChart.setOption({
          title: { text: '当前热销排行' },
          tooltip: {},
          xAxis: {
            data: this.typeData
          },
          yAxis: {},
          series: [{
            name: '销量',
            type: 'bar',
            data: this.salesData
          }]
        })
      }
    },
    mounted() {
      console.log(new Date())
      this.getTypeData()
    },
    mixins: [http]
  }
</script>

<style scoped>

</style>
