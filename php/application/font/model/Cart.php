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
                $newData  = array([
                    'id'    => $goodID,
                    'num'   => $num
                ]);
                $oldData = json_decode($user['User_Cart'],true);
                if(is_array($oldData))
                {
                  foreach($oldData as $row)
                  {
                      if($row["id"]=== $newData[0]["id"])
                      {
                        $newData[0]["num"] = (string)($row["num"]+$newData[0]["num"]);
;
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
                $res = Db::table('User')
                            ->where('User_Account',$account)->find();
            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return json($res);
        }
    }
