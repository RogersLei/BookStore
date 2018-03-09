<?php

    namespace app\admin\model;

    use think\Model;
    use think\Db;
    class Book extends Model
    {
        public function findAll()
        {
            try
            {
                $res = Db::query('select * from Book');

            }   catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
            }

            return $res;
        }

        public function updateBook($id,$name,$num,$price,$type)
        {
            try
            {
                Db::table('Book')
                    ->where('Book_ID',$id)
                    ->update([
                        'Book_Name'     =>  $name,
                        'Book_Price'    =>  $price,
                        'Book_Type'     =>  $type,
                        'Book_Stock'    =>  $num
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

        public function deleteBook($id)
        {
            try
            {
                Db::table('Book')
                    ->where('Book_ID',$id)
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
    }