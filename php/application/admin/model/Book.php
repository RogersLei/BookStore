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
                $res = Db::query('SELECT * FROM Book,Book_Type where Book_TypeID=BookType_ID;');

            }   catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
            }

            return $res;
        }

        public function findBookByID($id)
        {
            try
            {
                $res =  Db::query('SELECT * FROM Book,Book_Type where Book_TypeID=BookType_ID and Book_ID=:id',['id'=>$id]);
            } catch (Exception $e)
             {
                 $res = ["code" => 0,"msg" => $e->getMessage()];
                 Db::rollback();// 回滚事务
             }
             return $res;

        }

        public function updateBook($id,$name,$num,$price,$type)
        {
            try
            {
                $Book_TypeID = Db::query('SELECT BookType_ID FROM Book_Type where BookType_Name=:name',['name' => $type]);
                Db::table('Book')
                    ->where('Book_ID',$id)
                    ->update([
                        'Book_Name'     =>  $name,
                        'Book_Price'    =>  $price,
                        'Book_TypeID'   =>  $Book_TypeID,
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

        //-----------------------
        public function findBookType()
        {
            try
            {
                $res = Db::query('SELECT * FROM Book_Type');

            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return $res;
        }

        public function updateSort($id, $type)
        {
            try
            {
                Db::table('Book_Type')
                    ->where('BookType_ID',$id)
                    ->update([
                        'BookType_Name' => $type
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

        public function deleteSort($id)
        {
            try
            {
                $data = Db::table('Book')->where('Book_TypeID',$id)->value('Book_ID');
                if($data){
                    $res = [ "code" => 0, "msg" => '该类型还存在书籍'];
                } else {
                    try
                    {
                        Db::table('Book_Type')
                            ->where('BookType_ID',$id)
                            ->delete();
                        Db::commit();       // 提交事务
                        $res = ["code" => 200, "msg" => "OK"];
                    } catch (Exception $e)
                    {
                        $res = ["code" => 0,"msg" => $e->getMessage()];
                        Db::rollback();
                    }
                }

            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();
            }
            return json($res);
        }

        // ---------------
        public function salesTop10()
        {
            try
            {
                $res = Db::query('SELECT * FROM books.Book ORDER BY Book_Sales DESC LIMIT 0,10');

            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return $res;
        }
    }
