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
        $roleStatus=$this->getColumn("SELECT status FROM role WHERE id=?",array($roleId));
        return $roleStatus;
    }

    public function addUserActionLog($user,$node,$uri,$ip){
        if($node=="/index/index/index/"||$node=="/index/log/index/"||$node=="/index/log/list/"){
            return false;
        }else{
            $nodeName=$this->getColumn("SELECT name FROM menu where find_in_set(?, url);",array($node));
        }

        $data=array(
            'userID'=>$user['id'],
            'userName'=>$user['name'],
            'node'=>$node,
            'nodeName'=>$nodeName,
            'uri'=>$uri,
            'ip'=>$ip,
        );
        $this->insert('userLog', $data);
    }

}