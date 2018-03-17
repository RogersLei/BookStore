<?php

namespace app\font\controller;

use app\common\controller\Common;
use think\Request;
use think\Db;

class Base extends Common
{
    public function login()
        {
            $user = model('User');

            $account = input('account');
            $checkPass = input('pass');
            $data = $user->login($account,$checkPass);
            return $data;
        }

    public function register()
    {
        $user = model('User');
        $name = input('nickname');
        $account = input('account');
        $pass = input('pwd');
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        if($name != NULL && $account != NULL)
        {
            $data = $user->register($name, $account, $pass);
            return $data;
        }

    }
}
