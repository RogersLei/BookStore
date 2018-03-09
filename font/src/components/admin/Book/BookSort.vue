<template>
  <!--属于BUG页面-->
  <el-container>
    <el-table :data="tagData" style="width: 100%" height="500" ref="sortTable">
      <el-table-column type="selection" width="55"></el-table-column>
      <el-table-column prop="tag" label="分类名" width="180"></el-table-column>
      <!--<el-table-column prop="fname" label="父分类名"></el-table-column>-->
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
          <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog  title="修改书籍分类" :visible.syc="dialogVisible" @close="handleCloseDialog">
      <el-form>
        <el-form-item style="width: 100%">
          <el-select v-model="dialog.tag">
            <el-option v-for="item in tagData" :value="item.tag"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item style="width: 100%">
          <el-button type="info" @click="handleCloseDialog">取消</el-button>
          <el-button type="primary" @click="handleMakeEdit('Sort')">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </el-container>

</template>

<script>
    import common from '../../../assets/js/common'
    export default {
      name: "book-sort",
      data () {
          return {
            dialogVisible: false,
            dialog: {

            },
            tagData: []
          }
      },
      methods: {
        //牵扯到数据库的级联修改和删除
        findSort () {
          this.apiPost('admin/base/findBook').then((res)=>{
              if(res == '' || res == null){
                console.log('data error')
              } else {
                let tags = []
                res.forEach((item, index) => {
                  tags[index] = item.Book_Type
                })
                tags = Array.from(new Set(tags))
                tags.forEach((item, index) => {
                  this.tagData[index] = {
                    tag: tags[index]
                  }
                })
              }
          })
        }
      },
      mounted() {
        this.findSort()
      },
      mixins: [common]
    }
</script>

<style scoped>

</style>
