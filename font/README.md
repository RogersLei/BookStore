# book

> A Vue.js project

## Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

# build for production and view the bundle analyzer report
npm run build --report
```


>前端服务启动，默认会占用8080端口，所以在启动前端服务之前，请确认8080端口没有被占用。
>如果想替换前端默认端口，可修改config/index.js里面的dev对象的port参数，但不建议这么做。
>另外接口请求本地服务的端口是80端口，如果配置后端服务的时候启动的不是80端口，可在build/webpack.base.conf.js里修改DEV_HOST（开发环境请求地址）。
