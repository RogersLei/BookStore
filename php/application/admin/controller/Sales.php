<?php

namespace app\admin\controller;

use app\common\controller\Common;
use think\Request;
use think\Db;

class Sales extends Common
{
    public function salesTop10() {
        $book = model('Book');
        $data = $book->salesTop10();
        return json_encode($data);
    }
}
