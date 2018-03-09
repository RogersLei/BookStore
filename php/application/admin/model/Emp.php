<?php

    namespace app\admin\model;

    use think\Model;
    use think\Db;
    class Emp extends Model
    {
        public function login($account,$checkPass, $time)
        {
            try
            {
                $emp = Db::table('Emp')
                    ->where('Emp_Name',$account)->find();
                if($emp == NULL){
                    $res = ["code" => 0, "msg" => '账户不存在'];
                } else {
                    $old_pwd = $emp['Emp_Pwd'];
                    if(password_verify($checkPass, $old_pwd)){
                        //var_dump($emp);
                        try
                        {
                            Db::table('Emp')
                                ->where('Emp_ID', $emp['Emp_ID'])
                                ->update(['Emp_LastDate' => $time]);
                            $res = ["code" => 200, "msg" => '验证成功', "power" => $emp['Emp_Type']];
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

        public function findAll()
        {
            try
            {
                $res = Db::query('select * from Emp');

            }   catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
            }

            return json($res);
        }

        public function insertEmp($name, $pass, $age, $tel, $type)
        {
            $data = [
                'Emp_Name'  =>  $name,
                'Emp_Pwd'   =>  $pass,
                'Emp_Age'   =>  $age,
                'Emp_Tel'   =>  $tel,
                'Emp_Type'  =>  $type
            ];
            try
            {
                Db::table('Emp')->insert($data);
                Db::commit();
                $res = ["code" => 200, "msg" => "OK"];
            }   catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return json($res);
        }

        public function updateEmp($id, $name, $tel, $type)
        {
            try
            {
                Db::table('Emp')
                    ->where('Emp_ID',$id)
                    ->update([
                        'Emp_Name'      =>  $name,
                        'Emp_Tel'       =>  $tel,
                        'Emp_Type'      =>  $type
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

        public function deleteEmp($arr)
        {
            try
            {
                Db::table('Emp')
                    ->where('Emp_ID','in', $arr)
                    ->delete();
                Db::commit();       // 提交事务
                $res = ["code" => 200, "msg" => "OK"];
            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return json($res);
        }

        public function deleteEmpAll()
        {
            try
            {
                Db::table('Emp')->delete(true);
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
