<?php

namespace app\font\controller;

use app\common\controller\Common;
use think\Request;
use think\Db;

class Pay extends Common
{
    public function Pay()
    {
        $order = model('Order');
        $id = input('id');
        $books = input('books/a');
        $price = input('price');
        if($books != NULL && $id != NULL && $price != NULL){
          $data = $order->pay($id,$books,$price);
          return $data;
        }
    }

}
