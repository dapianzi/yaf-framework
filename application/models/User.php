<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:54
 */

class UserModel extends DbModel {

    /**
     * 获取用户信息
     * @param array $userinfo 用户信息
     * @return array
     */
    public function getUserInfo($userinfo) {
        $user=$this->getRow("SELECT * FROM {$this->table} WHERE name=?", array($userinfo['username']));
        if(empty($user)){
            //添加用户到系统
            $user=$this->addUser($userinfo);
        }
        return $user;
    }

    /**
     * 添加用户到系统
     * @param array $userinfo 用户信息
     * @return array
     */
    public function addUser($userinfo){
        $id=$this->add(array('name'=>$userinfo['username'],'nickName'=>$userinfo['cname']));
        if($id){
            $user=$this->getRow("SELECT * FROM {$this->table} WHERE name=?", array($userinfo['username']));
            return $user;
        }
    }

    public function getUserRoleStatus($userInfo){
        $roleId=$userInfo['roleId'];
        $roleStatus=$this->getColumn("SELECT status FROM role WHERE id=?", 0,array($roleId));
        return $roleStatus;
    }

    /**
     * 获取用户权限
     * @param int $userId 用户ID
     * return array
     */
    public function getUserAuth($userInfo){
        $roleId=$userInfo['roleId'];
        $roleStatus=$this->getColumn("SELECT status FROM role WHERE id=?", 0,array($roleId));
        if($roleStatus!=1){
            return array(
                'roleStatus'=>$roleStatus,
                'menu'=>''
            );
        }
        if($roleId=1){
            $sql='';
        }else{
            $sql='';
        }

        return array(
            'roleStatus'=>$roleStatus,
            'menu'=>''
        );
    }
}