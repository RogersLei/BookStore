<?php

    namespace app\font\model;

    use think\Model;
    use think\Db;
    class Order extends Model
    {
      public function createOrder($account,$order,$address,$tprice,$time)
      {
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
            'Order_StartTime' =>  $time
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
    }
