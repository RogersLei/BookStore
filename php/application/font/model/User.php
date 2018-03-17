<?php

    namespace app\font\model;

    use think\Model;
    use think\Db;
    class User extends Model
    {
        public function login($account,$checkPass)
        {
            try
            {
                $user = Db::table('User')
                    ->where('User_Account',$account)->find();
                if($user == NULL){
                    $res = ["code" => 0, "msg" => '账户不存在'];
                } else {
                    $old_pwd = $user['User_Pwd'];
                    if(password_verify($checkPass, $old_pwd)){
                        try
                        {
                            $res = ["code" => 200, "msg" => '验证成功', "name" => $user['User_Name']];
                        }  catch (Exception $e)
                        {
                            $res = ["code" => 0,"msg" => $e->getMessage()];
                            Db::rollback();// 回滚事务
                        }
                    } else {
                        $res = ["code" => 0, "msg" => '密码错误'];
                    }
                }
            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return json($res);
        }

        public function register($name, $account, $pass)
        {
            $data = [
                'User_Name'     => $name,
                'User_Account'   => $account,
                'User_Pwd'      => $pass
            ];
            try
            {
                Db::table('User')->insert($data);
                Db::commit();
                $res = ["code" => 200, "msg" => "OK"];
            }   catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return json($res);
        }
    }
