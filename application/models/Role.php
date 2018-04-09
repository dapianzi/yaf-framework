<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:54
 */

class RoleModel extends DbModel {

    public $table = 'role';

    function getUserGroup($page=1,$limit=20){
        $star=($page-1)*$limit;
        $userGroup=$this->getAll('select * from role order by id asc limit '.$star.','.$limit);
        $count=$this->getCount('select id from role');
        return array('userGroup'=>$userGroup,'count'=>$count);
    }

    function getUser($page=1,$limit=20){
        $star=($page-1)*$limit;
        $userGroup=$this->getAll('select * from user order by id asc limit '.$star.','.$limit);
        $count=$this->getCount('select id from user');
        foreach ($userGroup as $key=>$value){
            $role=$this->getUserGroupInfo($value['roleId']);
            $userGroup[$key]['roleName']=$role['name'];
        }
        return array('userGroup'=>$userGroup,'count'=>$count);
    }

    public function changeUserGroupValue($field,$id,$value){
        $status=$this->set($id,$field,$value);
        return $status;
    }

    public function changeUserRoleValue($id,$value){
        $roleId=$this->checkUserGroupExist($value);
        if(!$roleId){
            return false;
        }
        $status= $this->update('user', array('roleId'=>$roleId), array('id' => $id));
        return $status;
    }

    public function changeUserValue($field,$id,$value){
        $status= $this->update('user', array($field=>$value), array('id' => $id));;
        return $status;
    }


    public function delUserGroup($id){
        $status=$this->del($id);
        return $status;
    }

    public function editUserGroup($param=array(),$id){
        if(count($param)>0){
            $status=$this->edit($id,$param);
            return $status;
        }else{
            return false;
        }
    }

    function addUserGroup($param=array()){
        if(count($param)>0){
            $status=$this->add($param);
            return $status;
        }else{
            return false;
        }
    }

    function getAllAuth($ids){
        foreach ($ids as $id){
            $parentId=$this->getColumn('select parentId from menu where id='.$id);
            if(!in_array($parentId,$ids)){
                $ids[]=$parentId;
                $parentId2=$this->getColumn('select parentId from menu where id='.$parentId);
                if(!in_array($parentId2,$ids)){
                    $ids[]=$parentId2;
                }
            }
        }
        return $ids;
    }

    function checkUserGroupExist($name){
        $auth=$this->getColumn('select id from role where name=?',array($name));
        return $auth;
    }

    function getUserGroupInfo($id){
        $auth=$this->getRow('select * from role where id='.$id);
        return $auth;
    }
}