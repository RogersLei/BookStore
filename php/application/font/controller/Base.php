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

    public function updateUser()
    {
        $user = model('User');
        $account = input('account');
        $name = input('name');
        $src = input('src');
        $pass = input('pass');
        if($pass !== ""){
            $pass = password_hash($pass, PASSWORD_DEFAULT);
        }
        $data = $user->updateUser($account, $name, $src, $pass);
        return $data;
    }

    public function addToCartByID()
    {
        $cart = model('Cart');
        $account = input('account');
        $goodID = input('id');
        $num = input('num');
        $data = $cart->addToCartByID($account, $goodID, $num);
        return $data;
    }

    public function findCarts()
    {
        $cart = model('Cart');
        $account = input('account');
        $data = $cart->findCarts($account);
        return json_encode($data);
    }

    public function deleteCart()
    {
        $cart = model('Cart');
        $account = input('account');
        $data = $cart->deleteCart($account);
        return $data;
    }

    public function insertAddress()
    {
        $add = model('Address');
        $account = input('account');
        $name = input('name');
        $tel = input('tel');
        $address = input('address');
        $data = $add->insertAdd($account,$name,$tel,$address);
        return $data;
    }

    public function findAddress()
    {
        $add = model('Address');
        $account = input('account');
        $data = $add->findAddress($account);
        return json_encode($data);
    }

    public function deleteAddress()
    {
        $add = model('Address');
        $account = input('account');
        $name = input('name');
        $tel = input('tel');
        $address = input('address');
        $data = $add->delAdd($account,$name,$tel,$address);
        return $data;
    }


    public function getBanner()
    {
      $banner = model('Banner');
      $data = $banner->getBanner();
      return json_encode($data);
    }

    public function createOrder()
    {
      $order = model('Order');
      $account = input('account');
      $orderList = input('order/a');
      $address = input('address');
      $time = input('time');
      $tprice = input('tprice');
      if($account != NULL)
      {
        $data = $order->createOrder($account,$orderList,$tprice,$address, $time);
        return $data;
      }
    }

    public function findOrder()
    {
        $order = model('Order');
        $account = input('account');
        $data = $order->findOrder($account);
        return json_encode($data);
    }

    public function seachOrderByID()
    {
        $order = model('Order');
        $id = input('id');
        $data = $order->seachOrderByID($id);
        return json_encode($data);
    }

    public function deleteOrderByID()
    {
        $order = model('Order');
        $id = input('id');
        $data = $order->deleteOrderByID($id);
        return json_encode($data);
    }
}
