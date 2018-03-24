<?php

    namespace app\font\model;

    use think\Model;
    use think\Db;
    class Banner extends Model
    {
      public function getBanner()
      {
         try
         {
            $res = Db::query('select * from Banner');
         } catch (Exception $e)
         {
            $res = ["code" => 0,"msg" => $e->getMessage()];
            Db::rollback();// 回滚事务
         }
         return $res;
      }
    }
