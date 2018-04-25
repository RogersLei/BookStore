<?php

    namespace app\admin\model;

    use think\Model;
    use think\Db;
    class Banner extends Model
    {
        public function findBanner()
        {
           try
           {
               $res = Db::query('SELECT * FROM Banner');
           }   catch (Exception $e)
           {
               $res = ["code" => 0,"msg" => $e->getMessage()];
           }

           return $res;
        }

        public function addBanner($src)
        {
           try
           {
              $data = ['Banner_Src' => $src];
              Db::table('Banner')->insert($data);
              Db::commit();       // 提交事务
              $res = ["code" => 200, "msg" => "OK"];
           } catch (Exception $e)
           {
                $res = ["code" => 0,"msg" => $e->getMessage()];
                Db::rollback();// 回滚事务
           }
           return json($res);
        }

        public function updateBanner($id,$src,$type)
        {
           try
           {
              Db::table('Banner')
                  ->where('Banner_ID',$id)
                  ->update([
                      "Banner_Src"    => $src,
                      "Banner_Type"   => $type
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

        public function deleteBanner($id)
        {
            try
            {
                Db::table('Banner')
                    ->where('Banner_ID',$id)
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
