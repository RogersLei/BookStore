<template>
  <el-container>
    <el-header>
      <el-row>
        <el-col :span="8">
          <el-date-picker
            v-model="timeRange"
            type="daterange"
            align="right"
            unlink-panels
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            value-format="yyyy-MM-dd"
            :picker-options="pickerOptions">
          </el-date-picker>
        </el-col>
        <el-col :span="5" :offset="3">
          <el-input
            placeholder="用户昵称"
            v-model="name"
            clearable>
          </el-input>
        </el-col>
        <el-col :span="3" :offset="5">
          <el-button type="primary" @click="search()">搜索</el-button>
        </el-col>
      </el-row>
    </el-header>
    <el-main>
      <el-table
        @filter-change="filterTag"
        height=450
        :data="dataSource"
        style="width: 100%">
        <el-table-column
          align="center"
          prop="startTime"
          label="开始日期"
          width="150"
        >
        </el-table-column>
        <el-table-column
          align="center"
          label="用户账号"
          show-overflow-tooltip
          width="120">
          <template slot-scope="scope">
            {{scope.row.account}}({{scope.row.name}})
          </template>
        </el-table-column>
        <el-table-column
          align="center"
          prop="address"
          label="地址"
          show-overflow-tooltip
          width="150">
        </el-table-column>
        <el-table-column
          align="center"
          prop="price"
          label="价格"
          width="100">
        </el-table-column>
        <el-table-column
          column-key="status"
          :filter-multiple="false"
          align="center"
          prop="status"
          label="标签"
          width="100"
          :filters="statusArr"
          filter-placement="bottom-end">
          <template slot-scope="scope">
            <el-tag
              :type="scope.row.status === '已完成' ? 'primary' : 'success'"
              close-transition>{{scope.row.status}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="endTime"
          label="完成时间"
          width="150"
          align="center"
        >
        </el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button size="mini" @click="handleSend(scope.$index, scope.row)" v-if="scope.row.status === '待发货'" type="primary">发货</el-button>
            <el-button size="mini" @click="handleSearch(scope.$index, scope.row)" v-if="scope.row.status === '待收货'" type="success">查看物流</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage"
        :page-sizes="[5, 10, 20, 50]"
        :page-size="pageSize"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total">
      </el-pagination>
    </el-main>
  </el-container>

</template>

<script>
  import ElContainer from "element-ui/packages/container/src/main";
  import ElRow from "element-ui/packages/row/src/row";
  import http from "../../../assets/js/http"

  export default {
    components: {
      ElRow,
      ElContainer},
    name: "order-manage",
    data () {
      return {
        timeRange: [],
        name: '',
        pageSize: 5,
        currentPage:1,
        dataSource: [],
        total: 0,
        pickerOptions: {
          shortcuts: [{
            text: '最近一周',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近一个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
              picker.$emit('pick', [start, end]);
            }
          }, {
            text: '最近三个月',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
              picker.$emit('pick', [start, end]);
            }
          }]
        },
        statusArr: [{
          text: '待付款',
          value: '待付款'
        }, {
          text: '待发货',
          value: '待发货'
        }, {
          text: '待收货',
          value: '待收货'
        }, {
          text: '已完成',
          value: '已完成'
        }]
      }
    },
    methods: {
      _fetchList: function (tag='none') {
        // 搜索时，默认当前页为1，每页数量为10,得到结果更新total
        // [] 和 '' 时不做任何筛选 搜索全部内容
        let obj = {
          time: [].concat(this.timeRange),
          name: this.name,
          pageSize: this.pageSize,
          currentPage: this.currentPage,
          tag:tag,
        }

        this.apiPost('admin/sales/searchOrder',obj).then(res => {
          if(res.code === 200 ){
            this.total = res.total
            this.dataSource = res.data.map(item => {
              let user = {
                id: item.Order_ID,
                account: item.User_Account,
                name: item.User_Name,
                address: item.Order_Address,
                startTime: item.Order_StartTime,
                endTime: item.Order_EndTime,
                price: item.Order_Price,
                number: item.Order_Number,
                status: item.Order_Status
              }
              return user
            })

          } else {
            this.$message.error('请求数据失败')
            console.log(res.msg)
          }
        })
      },
      search: function () {
        this._fetchList()
      },
      filterTag: function(filters) {
        this.tag = filters.status[0]
        this.currentPage = 1
        this._fetchList(this.tag)
      },
      handleSearch: function (index, row) {
        this.$message.success(""+row.number);
      },
      handleSend: function (index, row) {
        let obj = {
          number: +new Date(),
          id: row.id
        }
        this.apiPost('admin/sales/sendOrder',obj).then( res=> {
          if(res.code === 200){
            this.$message.success('发货成功')
            this.dataSource = this.dataSource.map(item => {
              if(item.id === row.id) {
                item.number = obj.number
                item.status = '待收货'
              }
              return item
            })
          } else {
            this.$message.error('发货失败，请重试')
            console.log(res.msg)
          }
        })
      },
      handleSizeChange: function (val) {
        this.pageSize = val
        this.currentPage = 1
        this._fetchList(this.tag)
      },
      handleCurrentChange: function (val) {
        this.currentPage = val
        this._fetchList(this.tag)
      }
    },
    mounted () {
      this.search()
    },
    mixins: [http]
  }
</script>

<style scoped>

</style>
