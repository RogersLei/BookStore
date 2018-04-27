<template>
  <div>
    <el-table :data="bannerList" border>
      <el-table-column
        label="图片"
        width="550">
        <template slot-scope="scope">
          <img :src="scope.row.src" alt="" width="500" height="200">
        </template>
      </el-table-column>
      <el-table-column
        label="地址">
        <template slot-scope="scope">
          <el-input
            type="textarea"
            :rows="2"
            v-model="scope.row.src">
          </el-input>
        </template>
      </el-table-column>
      <el-table-column
        label="操作"
        width="100">
        <template slot-scope="scope">
          <div v-if="scope.row.id">
            <el-button @click="handleClick(scope.row)" type="text" size="small" v-if="scope.row.type === 0">首页展示</el-button>
            <el-button @click="handleClick(scope.row)" type="text" size="small" v-if="scope.row.type === 1">取消展示</el-button>
            <el-button @click="handleUpdate(scope.row)" type="text" size="small">更改</el-button>
            <el-button @click="handleDelete(scope.row)" type="text" size="small">删除</el-button>
          </div>
          <div v-else>
            <el-button @click="handleAdd(scope.row)" type="text" size="small">新增</el-button>
          </div>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
  import http from '../../../assets/js/http'
  export default {
    name: "banner",
    data () {
      return {
        active: 0,
        bannerList: []
      }
    },
    methods: {
      throttle (fn, delay) {
        if(!delay){
          delay = 3000
        }
        let _lastTime = null;

        return function (){
          let _nowTime = +new Date()
          if(_nowTime - _lastTime > delay || !_lastTime) {
            fn.apply(this, arguments)
            _lastTime = _nowTime
          } else {
            this.$message.warning(`请勿在${delay/1000}秒内连续点击`)
          }
        }
      },
      handleClick (value) {
        this.fn(value);
      },
      handleUpdate (value) {
        let obj = Object.assign({},value)
        this.apiPost('admin/base/updateBanner',obj).then(res => {
          if(res.code === 200) {
            this.$message.success('修改记录成功')
            setTimeout(()=>{
              this.$router.go(this.$route.fullPath)
            },1000)
          } else {
            this.$message,error('修改失败')
          }
        })
      },
      handleDelete (value) {
        let obj = Object.assign({},value)
        this.apiPost('admin/base/deleteBanner',obj).then(res => {
          if(res.code === 200) {
            this.$message.success('删除记录成功')
            setTimeout(()=>{
              this.$router.go(this.$route.fullPath)
            },1000)
          } else {
            this.$message,error('删除失败')
          }
        })
      },
      handleAdd (value) {
        if ( value.src === "") {
          this.$message.error('新增数据路径不能为空')
          return
        }
        let obj = Object.assign({},value)
        this.apiPost('admin/base/addBanner',obj).then(res => {
          if(res.code === 200) {
            this.$message.success('添加记录成功')
            setTimeout(()=>{
              this.$router.go(this.$route.fullPath)
            },1000)
          } else {
            this.$message,error('添加失败')
          }
        })
      }
    },
    mounted () {
      this.fn = this.throttle((value)=>{
        value.type = value.type === 1? 0 : 1
        let obj = Object.assign({},value)
        this.apiPost('admin/base/updateBanner',obj).then(res => {
          if(res.code === 200) {
            this.$message.success('修改记录成功')
            // setTimeout(()=>{
            //   this.$router.go(this.$route.fullPath)
            // },1000)
          } else {
            this.$message,error('修改失败')
          }
        })
      },2000);

      this.apiPost('admin/base/findBanner').then(res => {
        if(res.length>0){
          res.map(item => {
            item.id = item.Banner_ID
            item.src = item.Banner_Src
            item.type = item.Banner_Type
            delete item.Banner_ID
            delete item.Banner_Src
            delete item.Banner_Type
            this.bannerList.push(item)
            return item
          })
        } else {
          this.$message.warning('暂未配置任何banner信息')
        }
        this.bannerList.push({})
      })
    },
    mixins: [http]
  }
</script>

<style scoped>

</style>
