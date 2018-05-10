<template>
  <el-container style="min-width: 1020px">
    <el-header style="margin-top: 20px;">
      <el-button type="primary" style="float: left" @click="addBook()">添加书籍</el-button>
      <el-autocomplete placeholder="请输入内容" suffix-icon="el-icon-search"
                       v-model="searchBook" :fetch-suggestions="queryBook"  @select="handleSelect">
      </el-autocomplete>
      <el-button type="primary" style="float: right" @click="exportExcel()">导出为excel表格</el-button>
    </el-header>
    <el-main>
      <el-table :data=" filter == true ? tableData : tableData.slice((currentPage-1)*pageSize,currentPage*pageSize)"
                style="width: 100%" v-loading="loading" element-loading-text="拼命加载中" element-loading-spinner="el-icon-loading">
        <el-table-column type="selection" width="55"></el-table-column>
        <el-table-column prop="name" label="书名" width="180"></el-table-column>
        <el-table-column prop="num" label="存量"></el-table-column>
        <el-table-column prop="tag" label="类别" width="100"
                         :filters="tagData" filter-placement="bottom-end"
                         :filter-method="filterTag">
          <template slot-scope="scope">
            <el-tag :type="'success'" close-transition>{{scope.row.tag}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="price" label="价格" sortable></el-table-column>
        <el-table-column prop="sales" label="销量" sortable></el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
            <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row, 'Book')">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-main>
    <el-footer>
      <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange"
        :current-page="currentPage" :page-sizes="[10, 20, 50, 100]" :page-size="pageSize"
        layout="total, sizes, prev, pager, next, jumper" :total="booksTotal" v-show="showPage">
      </el-pagination>
    </el-footer>
    <el-dialog  title="修改书籍信息" :visible.syc="dialogVisible" @close="handleCloseDialog">
      <el-form>
        <el-form-item>
          <el-input type="text" v-model="dialog.name"></el-input>
        </el-form-item>
        <el-form-item>
          <el-input type="text" v-model="dialog.num"></el-input>
        </el-form-item>
        <el-form-item>
          <el-input type="text" v-model="dialog.price"></el-input>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-select v-model="dialog.tag">
            <el-option v-for="item in tagData" :value="item.value" :key="item.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-button type="info" @click="handleCloseDialog">取消</el-button>
          <el-button type="primary" @click="handleMakeEdit('Book')">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
    <el-dialog  title="添加书籍信息" :visible.syc="dialogAddVisible" @close="handleCloseAddDialog">
      <el-form>
        <el-form-item label="书籍名称">
          <el-input type="text" v-model="dialogAdd.name"></el-input>
        </el-form-item>
        <el-form-item label="书籍库存">
          <el-input type="text" v-model="dialogAdd.num"></el-input>
        </el-form-item>
        <el-form-item label="书籍价格">
          <el-input type="text" v-model="dialogAdd.price"></el-input>
        </el-form-item>
        <el-form-item label="书籍描述">
          <el-input type="text" v-model="dialogAdd.des"></el-input>
        </el-form-item>
        <el-form-item label="书籍图片地址">
          <el-input type="text" v-model="dialogAdd.img"></el-input>
        </el-form-item>
        <el-form-item style="width: 100%" label="书籍分类">
          <el-select v-model="dialogAdd.tag">
            <el-option v-for="item in tagData" :value="item.value" :key="item.value"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-button type="info" @click="handleCloseAddDialog">取消</el-button>
          <el-button type="primary" @click="handleMakeAdd()">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </el-container>
</template>

<script>
    import ElFooter from "element-ui/packages/footer/src/main";
    import http from '../../../assets/js/http'
    import common from '../../../assets/js/common'
    export default {
      components: {ElFooter},
      name: "books-list",
      data() {
        return {
          currentPage: 1,
          pageSize: 10,
          booksTotal: 0,
          books:[],
          searchBook:'',
          tableData:[],
          tagData:[],
          selects: [],
          filter: false,
          loading: true,
          dialogVisible: false,
          dialogAddVisible: false,
          dialog: {},
          dialogAdd: {},
          showPage: true
        }
      },
      methods: {
        loadBook () {  //加载后获取服务器所有书名  可以与tableData一同
          this.apiPost('admin/base/findBook').then((res)=>{
            if(res == '' || res == null){
              console.log('no data ')
            } else {
              let tags = []
              res.forEach((item, index) => {
                this.books[index] = {
                  id: item.Book_ID,
                  value: item.Book_Name
                }
                this.tableData[index] = {
                  id: item.Book_ID,
                  name: item.Book_Name,
                  num: item.Book_Stock,
                  tag: item.BookType_Name,
                  price: item.Book_Price,
                  sales: item.Book_Sales,
                }
                tags[index] = item.BookType_Name
                tags = Array.from(new Set(tags))
              })
              tags.forEach((item, index) => {
                this.tagData[index] = {
                  text: item,
                  value: item
                }
              })
              this.booksTotal = this.tableData.length
            }
          })
        },
        filterTag (value, row) {
          this.filter = true
          if(row.tag === value){

          }
          return row.tag === value
        },
        // handleSizeChange(val) {
        //   this.pageSize = val
        //   console.log(`每页 ${val} 条`);
        // },
        // handleCurrentChange(val) {
        //   this.currentPage = val
        //   console.log(`当前页: ${val}`);
        // },
        handleSelect (item) {
          //不选择不进行查询
          //寻找名为item.value的数据，替换tableData
          //如果item为''不改变tableData
          //此处更改tableData的数据
          this.loadBook()
          //this.booksTotal = this.tableData.length
          let books = this.tableData
          books.forEach((book, index) => {
            if(item.id == book.id){
              this.tableData = [{
                id: book.id,
                name: book.name,
                num: book.num,
                tag: book.tag,
                price: book.price
              }]

            }
          })

        },
        addBook () {
          this.dialogAddVisible = true
        },
        handleMakeAdd () {
          let obj = Object.assign({},this.dialogAdd)
          this.apiPost('admin/base/addBook',obj).then(res => {
            if(res.code === 200) {
              this.$message.success('添加成功')
              setTimeout(() => {
                 this.$router.go(this.$route.fullPath)
              },1000)
            } else {
              this.$message.error('添加失败')
            }
          })
        },
        handleCloseAddDialog () {
          this.dialogAddVisible = false
          this.dialogAdd = {}
        },
        exportExcel () {
          let obj = {
            post : "post"
          }
          this.apiPostExcel('admin/base/exportExcel',obj).then(res => {
            if (res.code === 200){
              let blob = new Blob([res.data], { type: 'application/x-xls' })
              console.log(blob)
              this.$message.success('导出成功')
            } else {
              this.$message.error('导出失败')
            }
          })
        }
      },
      mounted() {
        this.loadBook()
        setTimeout(()=>{
          this.loading = false
        },200)
      },
      mixins: [http, common]
    }
</script>

<style scoped>
</style>
