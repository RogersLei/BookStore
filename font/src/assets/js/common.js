import http from './http'

const apiOpration = {
  methods: {
    queryBook (string, callback) {
      let books = this.books
      let results = string ? books.filter(this.bookFilter(string)) : books
      callback(results)
    },
    bookFilter (string) {
      return (book) => {
        return (book.value.toLowerCase().indexOf(string.toLowerCase()) === 0);
      };
    },

    handleEdit (index, row) {
      this.dialogVisible = true
      this.dialog = {
        id: row.id,
        name: row.name,
        num: row.num,
        price:row.price,
        tag: row.tag,
        tel: row.tel
      }
    },
    handleDelete (index, row, locate) {
      this.$confirm('此操作将永久删除该行数据, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        let obj = { //后来需要完善权限处理
          id: row.id
        }
        if(locate === 'Emp'){
          let user = JSON.parse(sessionStorage.getItem('emp'))
          console.log(user.power, row.tag)
          if(user.power === row.tag){
            return
          }
          if (user.power === '管理员' && row.tag === '店长') {
            return
          } else if (user.power === '前台管理员' && (row.tag === '店长' || row.tag === '管理员')){
            return
          }
        }
        this.apiPost('admin/base/delete'+locate,obj).then((res)=>{
          if(res.code == 200){
            this.$message({
              type: 'success',
              message: '删除成功!'
            })
            this.$router.go(this.$route.path)   //暂时这么刷新
          } else {
            this.$message.error('删除数据失败'+res.msg)
          }
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    handleCloseDialog () {
      this.dialogVisible = false
      this.$message({
        type: 'info',
        message: '已取消'
      })
    },
    handleMakeEdit(locate) {
      let obj = Object.assign({},this.dialog)
      this.apiPost('admin/base/update'+locate,obj).then((res)=>{
        if(res.code == 200){
          this.$message({
            message: '恭喜你，修改成功',
            type: 'success'
          })
          this.$router.go(this.$route.path)   //暂时这么刷新
        } else {
          this.$message.error('更改数据失败')
        }
        this.dialogVisible = false
      })
    },
    handleSizeChange(val) {
      this.pageSize = val
      console.log(`每页 ${val} 条`);
    },
    handleCurrentChange(val) {
      this.currentPage = val
      console.log(`当前页: ${val}`);
    },
    handledelSelect (selects,locate) {
      this.$confirm('此操作将永久删除该行数据, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(()=>{
        this.$refs.uTable.selection.forEach( (item, index) => {
          this.selects[index] = item.id
        })
        //弹出确认框
        if (selects.length != 0) {
          let str = this.selects.join(',')
          let obj = {id: str}
          this.apiPost('admin/base/delete'+locate, obj).then((res) => {
            if(res.code == 200){
              this.$message({
                message: '删除成功',
                type: 'success'
              })
              this.$router.go(this.$route.path)   //暂时这么刷新
            } else {
              this.$message.error('删除失败')
            }
          })
          this.selects = []
        } else {
          this.$message.error('请选择要删除的信息')
        }
      })
    },
    handledelSelectAll (locate) {
      //判断权限
      this.$confirm('此操作将永久删除该行数据, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(()=> {
        let user = sessionStorage.getItem('user')
        if(user.power == '店长' || user.power == '管理员'){
          this.apiPost('admin/base/delete' + locate + 'All').then((res) => {
            if (res.code == 200) {
              this.$message({
                message: '删除成功',
                type: 'success'
              })
              this.$router.go(this.$route.path)   //暂时这么刷新
            } else {
              this.$message.error('删除失败')
            }
          })
        } else {
          this.$message.error('您的权限不够，请联系管理员')
        }
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        })
      })
    },
    handleGoHome () {
      this.$router.push({
        path: '/index'
      })
    },
  
    showInfo(id) {
      this.$router.push(`/index/goods?id=${id}`)
    },
    addToCart(id,num=1) {
      let user = JSON.parse(sessionStorage.getItem('user'))
      if(!user) {
        // alert('')
        this.$message.error('请在右上角先登陆')
        return
      } else {
        if(num === 0){
          this.$message.error('请输入数量')
          return
        }
        let obj = {
          account: user.account,
          id: id,
          num: num
        }
        this.apiPost('font/base/addToCartByID',obj).then((res)=>{
          if(res.code === 200 ){
            this.$message.success('添加成功')
          } else {
            this.$message.error('添加失败')
          }
        })
      }
    }
  },
  mixins: [http]
}

export default apiOpration
