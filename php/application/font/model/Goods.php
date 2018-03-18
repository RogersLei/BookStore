<?php

    namespace app\font\model;

    use think\Model;
    use think\Db;
    class Goods extends Model
    {
        public function addGoodByID($account, $goodID, $num, $book)
        {

            try
            {
                $user = Db::table('User')
                            ->where('User_Account',$account)->find();
                $newdata  = array([
                    'id'    => $goodID,
                    'num'   => $num
                ]);
                $olddata = json_decode($user['User_Cart'],true);
                print_r($newdata);
                print_r($olddata);

                foreach($olddata as $row)
                {
                    if($row['id'] == $newdata[0]['id'])
                    {
                        $newdata[0]['num'] += $row['num'];
                    }
                }
                print_r($newdata);
                print_r(array_merge($newdata, $olddata));
//                Db::table('User')
//                    ->where('User_Account',$account)
//                    ->update([
//                        'User_Cart' =>  $newdata
//                    ]);
//                Db::commit();       // 提交事务
                $res = ["code" => 200, "msg" => "OK"];
            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return json($res);
        }
    }
