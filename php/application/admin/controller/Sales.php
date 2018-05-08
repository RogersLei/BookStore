<?php

namespace app\admin\controller;

use app\common\controller\Common;
use think\Request;
use think\Db;

class Sales extends Common
{
    public function salesTop10()
    {
        $book = model('Book');
        $data = $book->salesTop10();
        return json_encode($data);
    }

    public function yesterdaySales()
    {
      $order = model('Order');
      $data = $order->yesterdaySales();
      return json_encode($data);
    }

    public function getSalesByTimeline()
    {
      $order = model('Order');
      $timeline = input('timeline');
      if($timeline !== null)
      {
        $data = $order->getSalesByTimeline($timeline);
        return json_encode($data);
      }
    }

    public function searchOrder()
    {
      $order = model('Order');
      $time = input('time/a');
      $name = input('name');
      $pageSize = input('pageSize');
      $currentPage = input('currentPage');
      $tag = input('tag');
      if($time !== null && $name !== null && $pageSize !== null && $currentPage !==null && $tag !== null)
      {
        $data = $order->searchOrder($time, $name, $pageSize, $currentPage, $tag);
        return json_encode($data);
      }
    }

    public function sendOrder()
    {
      $order = model('Order');
      $id = input('id');
      $number = input('number');
      if($number !== null)
      {
        $data = $order->sendOrder($id,$number);
        return $data;
      }
    }
}
