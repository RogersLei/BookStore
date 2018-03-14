<template>
  <el-container>
    <div>aaa</div>
    <div class="w">
      <div class="content">
        <div class="account-sidebar">
          <div class="avatar gray-box ">
            <div>
              <!--<img :src="userInfo.info.avatar"> -->
              <!--<h5>{{userInfo.info.name}}</h5>-->
            </div>
            <div class="box-inner">
              <ul class="account-nav">
                <li v-for="(item,i) in routeList" :key='i'>
                    <!--:class="{current:item.name===title}"-->
                    <!--@click="tab(item)"-->
                  <router-link :to="item.path">{{item.name}}</router-link>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="account-content">
          <router-view name="UserAll"></router-view>
        </div>
      </div>
    </div>
  </el-container>
</template>

<script>
  export default {
    name: "user",
    data(){
      return {
        routeList: [
          {
            name: '购物车', path: '/index/user/cart'
          },
          {
            name: '个人信息', path: '/index/user/info'
          },
          {
            name: '订单列表', path: '/index/user/order'
          },
          {
            name: '配送地址', path: '/index/user/address'
          },
        ]
      }
    },
    methods: {
    },
    mounted() {
      console.log(this.$route.params)
    },
    mixins: []
  }
</script>

<style scoped>

</style>
