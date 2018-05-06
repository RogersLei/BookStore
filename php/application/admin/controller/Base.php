<?php

namespace app\admin\controller;

use app\common\controller\Common;
use think\Request;
use think\Db;

class Base extends Common
{
    public function login()
    {
        $emp = model('Emp');

        $account = input('account');
        $checkPass = input('checkPass');
        $time = input('time');
        $data = $emp->login($account,$checkPass, $time);
        return $data;
    }

    //----------------------------
    public function findEmp()
    {
       $emp = model('Emp');
       $data = $emp->findAll();
       return $data;

    }

    public function insertEmp()
    {
        $emp = model('Emp');
        $name = input('name');
        $pass = input('pass');
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        $age = input('age');
        $tel = input('tel');
        $type = input('type');
        if($name != NULL && $age != NULL || $tel != NULL || $type != NULL)
        {
            $data = $emp->insertEmp($name, $pass, $age, $tel, $type);
            return $data;
        }

    }

    public function updateEmp()
    {
        $emp = model('Emp');
        $id = input('id');
        $name = input('name');
        $tel = input('tel');
        $type = input('tag');
        if($id != NULL && $name != NULL & $tel != NULL && $type != NULL)
        {
          $data = $emp->updateEmp($id, $name, $tel, $type);
          return $data;
        }
    }

    public function deleteEmp()
    {
        $emp = model('Emp');
        $str = input('id');
        $arr = explode(",",$str);
        $data = $emp->deleteEmp($arr);
        return $data;
    }

    public function deleteEmpAll()
    {
        $emp = model('Emp');
        $data = $emp->deleteEmpAll();
        return $data;
    }

    //--------------------------------------
    public function findBook()
    {
       $book = model('Book');
       $data = $book->findAll();
       return json_encode($data);
    }

    public function findBookByID()
    {
        $book = model('book');
        $id = input('id');
        $data = $book->findBookByID($id);
        return json_encode($data);
    }

    public function updateBook()
    {
        $book = model('Book');
        $id = input('id');
        $name = input('name');
        $num = input('num');
        $price = input('price');
        $type = input('tag');
        if($id != NULL && $name != NULL && $num != NULL && $price != NULL && $type != NULL)
        {
          $data = $book->updateBook($id,$name,$num,$price,$type);
          return $data;
        }

    }


    public function deleteBook()
    {
        $book = model('Book');
        $id = input('id');
        $data = $book->deleteBook($id);
        return $data;
    }

    //-----------------------------

    public function updateSort()
    {
        $book = model('Book');
        $id = input('id');
        $type = input('tag');
        $data = $book->updateSort($id, $type);
        return $data;
    }

    public function findBookType()
    {
        $book = model('book');
        $data = $book->findBookType();
        return json_encode($data);
    }

    public function deleteSort()
    {
        $book = model('Book');
        $id = input('id');
        $data = $book->deleteSort($id);
        return $data;
    }

    public function findBanner()
    {
        $banner = model('Banner');
        $data = $banner->findBanner();
        return json_encode($data);
    }

    public function addBanner()
    {
        $banner = model('Banner');
        $src = input('src');
        if($src !== null && $src !== '')
        {
          $data = $banner->addBanner($src);
          return $data;
        }
    }

    public function updateBanner()
    {
        $banner = model('Banner');
        $id = input('id');
        $src = input('src');
        $type = input('type');
        if($src !== null && $src !== '' && $type !== null && $type !== '')
        {
          $data = $banner->updateBanner($id,$src,$type);
          return $data;
        }

    }

    public function deleteBanner()
    {
        $banner = model('Banner');
        $id = input('id');
        $data = $banner->deleteBanner($id);
        return $data;
    }


    public function recommend()
    {
        $book = model('Book');
        $arr = input('books/a');
        if($arr !== null)
        {
            $data = $book->recommend($arr);
            return json_encode($data);
        }
    }
}
