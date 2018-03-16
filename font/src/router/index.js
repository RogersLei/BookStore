
import LoginAdmin from '@/components/admin/Login.vue'
import Home from '@/components/admin/Home.vue'
import Index from '@/components/font/Home.vue'
import NotFound from '@/components/404.vue'

import Main from '@/components/aaa.vue'

import EmpList from '@/components/admin/User/UserList.vue'
import EmpAdd from '@/components/admin/User/UserAdd.vue'
import BookList from '@/components/admin/Book/BookList.vue'
import BookSort from '@/components/admin/Book/BookSort.vue'
import SalesRank from '@/components/admin/Sale/SalesRank.vue'
import SalesCount from '@/components/admin/Sale/SalesCount.vue'
import FontHome from '@/components/font/show/Home.vue'
import Goods from '@/components/font/show/Goods.vue'
import All from '@/components/font/show/All.vue'
import Cart from '@/components/font/user/Cart.vue'
import User from '@/components/font/User/User.vue'
import Order from '@/components/font/User/OrderList.vue'
import Address from '@/components/font/User/AddressList.vue'
import Info from '@/components/font/User/Info.vue'

let routes = [
  {//后台管理界面start
    path: '/login',
    name: 'AdminIndex',
    component: LoginAdmin,
    hidden: true
  },
  {
    path: '/admin',
    component: Home,
    name: '员工',
    iconCls: '',//图标样式class
    children: [
      { path: '/userM', component: EmpAdd, name: '添加员工' },
      { path: '/userL', component: EmpList, name: '员工列表' }
    ]
  },
  {
    path: '/admin',
    component: Home,
    name: '书籍',
    iconCls: '',
    children: [
      { path: '/bookS', component: BookSort, name: '书籍分类' },
      { path: '/bookL', component: BookList, name: '书籍列表' }
    ]
  },
  {
    path: '/admin',
    component: Home,
    name: '前台',
    iconCls: '',
    children: [
      { path: '/banner', component: Main, name: '轮播图' },
      { path: '/saleF', component: Main, name: '店长推荐' }
    ]
  },
  {
    path: '/admin',
    component: Home,
    name: '销售',
    iconCls: '',
    children: [
      { path: '/saleC', component: SalesCount, name: '销售统计' },
      { path: '/saleR', component: SalesRank, name: '销售排行' }
    ]
  },


  {//前台销售界面start
    path: '/index',
    name: 'Index',
    component: Index,
    hidden: true,
    children: [
      {
        path: '/index',
        components: {
          Home: FontHome,
        },
        hidden: true,
      },
      {
        path: '/index/goods',
        components: {
          Home: Goods,
        },
        hidden: true,
      },
      {
        path: '/index/all',
        components: {
          Home: All,
        },
        hidden: true,
      },
      {
        path: '/index/user/',
        components: {
          Home: User,
        },
        children: [
          {
            path: 'info',
            name: '个人信息',
            components: {
              UserAll: Info
            }
          },
          {
            path: 'order',
            name: '订单信息',
            components: {
              UserAll: Order
            }
          },
          {
            path: 'cart',
            name: '购物清单',
            components: {
              UserAll: Cart
            }
          },
          {
            path: 'address',
            name: '配送地址',
            components: {
              UserAll: Address
            }
          }
        ],
      },
    ]
  },


  {//页面错误信息
    path: '/404',
    name: 'NotFound',
    component: NotFound,
    hidden: true
  },
  {
    path: '*',
    redirect: {
      path: '/404'
    },
    hidden: true
  }
]


export default routes
