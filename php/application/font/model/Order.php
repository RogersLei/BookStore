<?php

    namespace app\font\model;

    use think\Model;
    use think\Db;
    class Order extends Model
    {
      public function createOrder($account,$order,$tprice,$address,$time)
      {
        Db::startTrans();
        try
        {
          $user = Db::table('User')
                      ->where('User_Account',$account)->find();
          $userID = $user['User_ID'];
          $order = json_encode($order);
          $data = [
            'Order_User'      =>  $userID,
            'Order_Books'     =>  $order,
            'Order_Price'     =>  $tprice,
            'Order_Status'    =>  '待付款',
            'Order_StartTime' =>  $time,
            'Order_Address'   =>  $address
          ];
          Db::table('Order_List')->insert($data);
          Db::commit();       // 提交事务
          $res = ["code" => 200, "msg" => "OK"];
        } catch (Exception $e)
        {
            $res = ["code" => 0,"msg" => $e->getMessage()];
            Db::rollback();// 回滚事务
        }
        return json($res);
      }


      public function findOrder($account)
      {
        Db::startTrans();
        try
        {
            $user = Db::table('User')
                        ->where('User_Account',$account)->find();
            $userID = $user['User_ID'];
            $res = Db::query('select * from Order_List where Order_User=:id order by Order_StartTime desc',['id'=>$userID]);
            foreach($res as $key => $val)
            {
              $time  = date("Y-m-d H:i:s",strtotime("-2 day"));
              if($val['Order_Status'] === '待收货' && $val['Order_StartTime'] < $time)
              {
                $res[$key]['Order_play'] = 1;
              }
            }
        } catch (Exception $e)
        {
            $res = ["code" => 0,"msg" => $e->getMessage()];
            Db::rollback();// 回滚事务
        }
        return $res;
      }

      public function seachOrderByID($id)
      {
        Db::startTrans();
        try
        {
            $res = Db::table('Order_List')
                        ->where('Order_ID',$id)->find();
        } catch (Exception $e)
        {
            $res = ["code" => 0,"msg" => $e->getMessage()];
            Db::rollback();// 回滚事务
        }
        return $res;
      }

      public function deleteOrderByID($id)
      {
        Db::startTrans();
        try
        {
          Db::table('Order_List')->where('Order_ID',$id)->delete();
          Db::commit();       // 提交事务
          $res = ["code" => 200, "msg" => "OK"];
        } catch (Exception $e)
        {
          $res = ["code" => 0,"msg" => $e->getMessage()];
          Db::rollback();// 回滚事务
        }
        return $res;
      }



     public function Pay($id,$books,$price)
     {
          $user = Db::table('Order_List')
                        ->where('Order_Id',$id)
                        ->find();
          $userID = $user['Order_User'];
          $User = Db::table('User')
                        ->where('User_ID',$userID)
                        ->find();
          $oldB = $User['User_Balance'];
          $newB = $oldB - $price;
          if($newB>=0)
          {
              foreach ($books as $key => $value){
                $bookID = $value['id'];
                $num = (int)$value['num'];
                try
                {
                  $book = Db::table('Book')
                              ->where('Book_ID',$bookID)
                              ->find();
                  $stock = $book['Book_Stock'];
                  $newstock = $stock-$num;
                  if($newstock >= 0)
                  {
                    Db::startTrans();
                    try
                    {
                      Db::table('Book')
                          ->where('Book_ID', $bookID)
                          ->update(['Book_Stock' => $newstock]);
                      Db::commit();
                      $flag = true;
                    } catch (Exception $e)
                    {
                      $res = ["code" => 0,"msg" => $e->getMessage()];
                      $flag = false;
                      Db::rollback();// 回滚事务
                    }
                  } else
                  {
                    $res = ["code" => 0,"msg" => '库存不足'];
                    $flag = false;
                  }
                } catch (Exception $e)
                {
                  $res = ["code" => 0,"msg" => $e->getMessage()];
                  $flag = false;
                  Db::rollback();// 回滚事务
                }
              }
          } else
          {
            $flag = false;
            $res = ["code" => 0, "msg" => "账户余额不足"];
          }


          if($flag)
          {
                Db::table('Order_List')
                    ->where('Order_ID', $id)
                    ->update(['Order_Status' => '待发货']);
                Db::table('User')
                      ->where('User_ID',$userID)
                      ->update(['User_Balance' => $newB]);
                Db::commit();
                $res = ["code" => 200, "msg" => "OK"];
          }
          return json($res);
     }

     public function finishOrder($id)
     {
        $aaa = Db::table('Order_List')->where('Order_ID',$id)->find();
        $arr = json_decode($aaa['Order_Books']);
        try
        {
            foreach($arr as $key)
            {
              $book_id = $key->id;
              $book_num = $key->num;
              $book = Db::table('Book')
                          ->where('Book_ID',$book_id)
                          ->find();
              $sales = $book['Book_Sales'];
              $newSales = $sales + $book_num;
              Db::table('Book')
                    ->where('Book_ID',$book_id)
                    ->update(['Book_Sales' => $newSales]);
            }
            $time  = date("Y-m-d H:i:s");
            Db::table('Order_List')
                    ->where('Order_ID',$id)
                    ->update([
                      'Order_Status' => '已完成',
                      'Order_EndTime' =>  $time
                    ]);
            Db::commit();
            $res = ["code" => 200, "msg" => "OK"];
        } catch (Exception $e)
        {
            $res = ["code" => 0,"msg" => $e->getMessage()];
            Db::rollback();// 回滚事务
        }
        return $res;
     }
   }
