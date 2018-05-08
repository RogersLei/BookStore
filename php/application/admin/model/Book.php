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

        public function findBookByName($name)
        {
           try
           {
               $map['Book_Name'] = ['like','%'.$name.'%'];
               $value =  Db::table('Book')
                             ->where($map)
                             ->select();
               $res = ["code" => 200, "data" => $value];
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

        public function recommend($books)
        {
            try
            {
                $arr = array(); //存放需要推荐的分类id
                $recommend = array();
                forEach($books as $key)
                {
                    $typeID = Db::table('Book')->where('Book_ID',$key)->column('Book_TypeID');
                    array_push($arr,$typeID[0]);
                }
                // 求出众数
                $maxCount = [];
                $len = count($arr);
                $zhongshu = '';
                for($i=0; $i<$len; $i++)
                {
                    $count = 0;
                    for($j=0; $j<$len; $j++)
                    {
                        if($arr[$i] == $arr[$j])
                        {
                            $count++;
                            $maxCount[$arr[$i]] = $count ;
                        }
                    }
                }


                $cishu = max($maxCount);
                if($cishu > 1 )
                {
                    foreach($maxCount as $k => $v)
                    {
                        if($v == $cishu)
                        {
                            $zhongshu = $k;
                            $maxArr = Db::table('Book')
                                          ->where('Book_TypeID',$k)
                                          ->order('Book_Sales','desc')
                                          ->limit(8)
                                          ->select();
                            // maxArr 为将浏览最多的typeid 在数据库中挑选出5本
                            forEach($maxArr as $key)
                            {
                                array_push($recommend,$key);
                            }
                        } else
                        {
                            $otherArr = Db::table('Book')
                                            ->where('Book_TypeID',$k)
                                            ->order('Book_Sales','desc')
                                            ->limit(2)
                                            ->select();
                            // otherArr 为将其他浏览过的typeid 在数据库中各挑选出2本
                            forEach($otherArr as $key)
                            {
                                array_push($recommend,$key);
                            }
                        }
                    }
                }else
                {
                    $zhongshu = '无众数';
                    $cishu = '值出现的个数都唯一！';
                    forEach($maxCount as $k => $v)
                    {
                        $otherArr = Db::table('Book')
                                    ->where('Book_TypeID',$k)
                                    ->order('Book_Sales','desc')
                                    ->limit(2)
                                    ->select();
                        // otherArr 为将其他浏览过的typeid 在数据库中各挑选出2本
                        forEach($otherArr as $key)
                        {
                            array_push($recommend,$key);
                        }
                    }
                }
                $res = ["code" => 200, "data" => $recommend, "count" => count($recommend)];

            } catch (Exception $e)
            {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
            }
            return $res;
        }
    }
