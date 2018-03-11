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
        DataSource: [],
        typeData: [],
        salesData: [],
        // loading: 0,
      }
    },
    methods: {
      getTypeData(){
        this.apiPost('admin/sales/salesTop10').then((res) => {
          if(res === '' || res === null) {
            console.log('no data')
          } else {
            res.forEach((item, index) => {
              this.DataSource[index] = {
                name: item.Book_Name,
                sales: item.Book_Sales,
              }
            })
            this.dealData(this.DataSource)
          }
        })
      },
      dealData(data) {
        this.typeData = data.map((item) => {
          return item.name
        })
        this.salesData = data.map((item) => {
          return item.sales
        })
        this.drawLine()
        // this.loading = new Date()
        // console.log(this.loading)
      },
      drawLine() {
        let myChart = this.$echarts.init(document.getElementById('myChart'),'light')
        myChart.setOption({
          title: { text: '书籍Top10' },
          tooltip: {},
          legend: {},
          xAxis: {
            data: this.typeData.map((item)=>{
              return item
            }),
            axisLabel: {
              rotate: -30,
              fontSize: 10,
            }
          },
          grid: {
            bottom: '30%',
          },
          yAxis: {},
          series: [{
            name: '销售量',
            type: 'bar',
            data: this.salesData,
            label: {
              show: true,
              position: 'top',
            }
          }]
        })
      }
    },
    mounted() {
      this.getTypeData()
    },
    mixins: [http]
  }
</script>

<style scoped>

</style>
