<?php
// +----------------------------------------------------------------------
// | Description: 解决跨域问题
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\Controller;

header("Content-Type:text/html;charset=utf-8");

// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

class Common extends Controller
{
    public function _initalize()
    {

    }
}