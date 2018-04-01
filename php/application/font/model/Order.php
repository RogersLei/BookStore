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
            $res = Db::query('select * from Order_List where Order_User=:id',['id'=>$userID]);
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
                Db::table('Order_List')
                    ->where('Order_ID', $id)
                    ->update(['Order_Status' => '待发货']);
                Db::commit();
                $res = ["code" => 200, "msg" => "OK"];
              } catch (Exception $e)
              {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
              }
            } else
            {
              $res = ["code" => 0,"msg" => '库存不足'];
            }
          } catch (Exception $e)
          {
            $res = ["code" => 0,"msg" => $e->getMessage()];
            Db::rollback();// 回滚事务
          }
        }
        return json($res);
     }
   }
