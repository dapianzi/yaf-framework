<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:54
 */

class LogModel extends DbModel {

    public $table = 'userLog';


    function getLog($time,$page=1,$limit=20){
        $where=' where status=1 ';
        if($time!=''&&is_array($time)){
            $where=' and adate>="'.$time[0].'" and  adate<="'.$time[1].'"';
        }
        $star=($page-1)*$limit;
        $Log=$this->getAll('select * from userLog '.$where.' order by adate desc limit '.$star.','.$limit);
        $count=$this->getCount('select id from userLog '.$where);
        return array('log'=>$Log,'count'=>$count);
    }

    public function changeUserGroupValue($field,$id,$value){
        $status=$this->set($id,$field,$value);
        return $status;
    }



    public function delLog($time){
        if($time!=''){
            print_r('update userLog set status=-1 where adate<="'.$time.'"');die;
            $status=$this->execute('update userLog set status=-1 where adate<="'.$time.'"');
            return $status;
        }
        return false;
    }

}