// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an ali
import Vue from 'vue'
import App from './App'
import axios from 'axios'
import VueRouter from 'vue-router'
import routes from './router/index'

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'

import echarts from 'echarts'

//import http from './assets/js/http'

Vue.use(ElementUI)
Vue.use(VueRouter)

Vue.prototype.$echarts = echarts
//Vue.use(ElementUI, { locale })
//import locale from 'element-ui/lib/locale/lang/en'

Vue.config.productionTip = false

axios.defaults.baseURL = "http://localhost:8888"

const router = new VueRouter({
  routes
})

router.beforeEach((to, from, next) => {
  //NProgress.start();
  if (to.path == '/login') {  // 目的路由为／login时，即退出，所以清除session用户信息
    sessionStorage.removeItem('user');
  }
  let user = JSON.parse(sessionStorage.getItem('user')); //获取session中用户信息
  if (to.path == '/index') {
    next()
  } else if (!user && to.path != '/login') { // 如果不存在用户信息，并且想跳转到其他页面则
    next({ path: '/login' }) // 中断当前导航，执行新的导航
  } else {
    next() // 进行下一个钩子
  }
})

window.axios = axios
const bus = new Vue()
window.bus = bus


/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
