<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:54
 */

class UserModel extends DbModel {

    public function getUserInfo($userinfo) {
        $user=$this->getRow("SELECT * FROM {$this->table} WHERE name=?", array($userinfo['username']));
        if(empty($user)){
            //添加用户到系统
            $user=$this->addUser($userinfo);
        }
        return $user;
    }

    public function addUser($userinfo){
        $id=$this->add(array('name'=>$userinfo['username'],'nickName'=>$userinfo['cname']));
        if($id){
            $user=$this->getRow("SELECT * FROM {$this->table} WHERE name=?", array($userinfo['username']));
            return $user;
        }
    }

    public function getUserAuth($userId){

    }

}