<?php

    namespace app\font\model;

    use think\Model;
    use think\Db;
    class Address extends Model
    {
        public function insertAdd($account, $address)
        {
            var_dump($account);
            var_dump($address);
            print_r($account);
            print_r($address);
        }
    }
