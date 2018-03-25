<?php

    namespace app\font\model;

    use think\Model;
    use think\Db;
    class Address extends Model
    {
        public function insertAdd($account, $name, $tel, $address)
        {
            try
            {
                $user = Db::table('User')
                            ->where('User_Account',$account)->find();
                $newData  = array([
                    'name'    => $name,
                    'tel'     => $tel,
                    'address'  => $address
                ]);
                $oldData = json_decode($user['User_Address'],true);
                if(is_array($oldData))
                {
                  foreach($oldData as $row)
                  {
                    array_push($newData, $row);
                  }
                }
                var_dump($newData);
                $newData = json_encode($newData);
                var_dump($newData);
                Db::table('User')
                    ->where('User_Account',$account)
                    ->update([
                        'User_Address' =>  $newData
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

        public function findAddress($account)
        {
            try
            {
                $user = Db::table('User')
                            ->where('User_Account',$account)->find();
                $res = json_decode($user['User_Address'],true);
            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return $res;
        }

        public function delAdd($account, $name, $tel, $address)
        {
            try
            {
                $user = Db::table('User')
                            ->where('User_Account',$account)->find();
                $oldData = json_decode($user['User_Address'],true);
                if(is_array($oldData))
                {
                  foreach($oldData as $key => $row)
                  {
                    if($row['name'] === $name && $row['tel'] === $tel && $row['address'] === $address)
                    {
                      array_splice($oldData,$key,1);
                    }
                  }
                }
                $newData = json_encode($oldData);
                Db::table('User')
                    ->where('User_Account',$account)
                    ->update([
                        'User_Address' =>  $newData
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
