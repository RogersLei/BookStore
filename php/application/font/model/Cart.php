<?php

    namespace app\font\model;

    use think\Model;
    use think\Db;
    class Cart extends Model
    {
        public function addToCartByID($account, $goodID, $num)
        {
            try
            {
                $user = Db::table('User')
                            ->where('User_Account',$account)->find();
                $book_price = Db::table('Book')
                                  ->where('Book_ID',$goodID)->value('Book_Price');
                $book_name = Db::table('Book')
                                  ->where('Book_ID',$goodID)->value('Book_Name');
                $newData  = array([
                    'id'      => $goodID,
                    'name'    => $book_name,
                    'num'     => $num,
                    'pprice'  => $book_price,
                    'tprice'  => $book_price*$num
                ]);
                $oldData = json_decode($user['User_Cart'],true);
                if(is_array($oldData))
                {
                  foreach($oldData as $row)
                  {
                      if($row["id"]=== $newData[0]["id"])
                      {
                        $newData[0]["num"] = (string)($row["num"]+$newData[0]["num"]);
                        $newData[0]["tprice"] = (string)($newData[0]["num"]*$newData[0]["pprice"]);
                      } else
                      {
                        array_push($newData, $row);
                      }
                  }
                }
                $newData = json_encode($newData);
                Db::table('User')
                    ->where('User_Account',$account)
                    ->update([
                        'User_Cart' =>  $newData
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


        public function findCarts($account)
        {
            try
            {
                $user = Db::table('User')
                            ->where('User_Account',$account)->find();
                $res = json_decode($user['User_Cart'],true);
            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return $res;
        }

        public function deleteCart($account)
        {
            try
            {
                Db::table('User')
                    ->where('User_Account',$account)
                    ->update(['User_Cart' => '']);
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
