<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:54
 */

class UserModel extends DbModel {

    public function getUserInfo($username) {
        $user=$this->getRow("SELECT * FROM {$this->table} WHERE name=?", array($username));
        if(empty($user)){
            //添加用户到系统
            $user=$this->addUser($username);
        }
        return $user;
    }

    public function addUser($username){
        $id=$this->add(array('name'=>$username));
        if($id){
            $user=$this->getRow("SELECT * FROM {$this->table} WHERE name=?", array($username));
            return $user;
        }
    }

}