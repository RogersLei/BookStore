<?php

    namespace app\admin\model;

    use think\Model;
    use think\Db;
    class Order extends Model
    {
      public function yesterdaySales()
      {
          $yesterday  =  date("Y-m-d",strtotime("-1 day"));
          $today      =  date("Y-m-d");

          try
          {
              $res = Db::query('SELECT * FROM books.Order_List where Order_Status = "已完成" AND Order_EndTime BETWEEN :yesterday AND :today ORDER BY Order_Price DESC',['yesterday' => $yesterday, 'today' => $today]);

          } catch (Exception $e)
          {
              $res = ["code" => 0,"msg" => $e->getMessage()];
              Db::rollback();// 回滚事务
          }
          return $res;
      }

      public function getSalesByTimeline($timeline)
      {
        $time  =  date("Y-m-d",strtotime("-{$timeline} day"));
        $today =  date("Y-m-d");
        try
        {
            $res = Db::query('SELECT * FROM books.Order_List where Order_Status = "已完成" AND Order_EndTime BETWEEN :yesterday AND :today ORDER BY Order_EndTime',['yesterday' => $time, 'today' => $today]);

        } catch (Exception $e)
        {
            $res = ["code" => 0,"msg" => $e->getMessage()];
            Db::rollback();// 回滚事务
        }
        return $res;
      }

      public function searchOrder($time, $name, $pageSize, $currentPage, $tag)
      {
        $currentPage = $currentPage - 1;
        $startIndex = $currentPage * $pageSize;
        $map = [];
        if(count($time) !== 0)
        {
          $startTime = $time[0];
          $endTime = $time[1];
          $map['Order_StartTime'] = ['between', [$startTime,$endTime]];
        }
        if($name !== ''){
          $map['User_Name'] = ['like','%'.$name.'%'];
        }
        if($tag !== 'none')
        {
          $map['Order_Status'] = $tag;
        }
        try
        {
           $res = Db::table(['Order_List', 'User'])
                      ->where('Order_User = User_ID')
                      ->where($map)
                      ->order('Order_StartTime desc')
                      ->select();
           $num = count($res);
           $res = Db::table(['Order_List', 'User'])
                                 ->where('Order_User = User_ID')
                                 ->where($map)
                                 ->order('Order_StartTime desc')
                                 ->limit($startIndex,$pageSize)
                                 ->select();
           $res = ["code" => 200, "data" => $res , "total" => $num];
        } catch (Exception $e)
        {
           $res = ["code" => 0,"msg" => $e->getMessage()];
           Db::rollback();// 回滚事务
        }
        return $res;
      }


      public function sendOrder($id,$number)
      {
         try
         {
            Db::table('Order_List')
                ->where('Order_ID',$id)
                ->update([
                    "Order_Number"    => $number,
                    "Order_Status"    => "待收货"
                ]);
            Db::commit();       // 提交事务
            $res = ["code" => 200, "msg" => "OK"];
         } catch (Exception $e)
         {
              $res = ["code" => 0,"msg" => $e->getMessage()];
              Db::rollback();// 回滚事务
         }
         return json($res);
      }

    }
