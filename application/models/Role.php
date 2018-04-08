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

    public function changeUserGroupValue($field,$id,$value){
        $status=$this->set($id,$field,$value);
        return $status;
    }

    public function delUserGroup($id){
        $status=$this->del($id);
        return $status;
    }

    function addUserGroup($param=array()){
        if(count($param)>0){
            $status=$this->add($param);
            return $status;
        }else{
            return false;
        }
    }


}