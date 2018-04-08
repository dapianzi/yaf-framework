<?php
/**
 * Created by PhpStorm.
 * User: sky / ouyangdongming
 * Date: 2018/4/3
 * Time: 13:59
 */
class MenuModel extends DbModel {
    // table name
    public $table = 'menu';

    public $confName = 'mysql';

    /**
     * 获取菜单
     * return array
     */
    public function getMenu($parentId='',$level="1"){
        $where='where status=1';
        if($parentId!=''){
            $where.=' and parentId='.$parentId;
        }
        if($level!=''){
            $where.=' and level in ('.$level.')';
        }
        $menu_db=$this->getAll('select * from menu '.$where.' order by level asc,listorder asc');
        return $menu_db;
    }

    public function changeMenuValue($field,$id,$value){
        $status=$this->set($id,$field,$value);
        return $status;
    }

    public function delMenu($id){
        $status=$this->del($id);
        return $status;
    }

    public function get_level($id){
        $level=$this->getColumn('select level from menu where id='.$id);
        return $level+1;
    }

    /**
     * 增加菜单
     * @return string
     */
    public function addMenu($param=array()){
        if(count($param)>0){
            $status=$this->add($param);
            return $status;
        }else{
            return false;
        }
    }

    /**
     * 获取菜单信息
     * @return string
     */
    public function getMenuInfo($id){
        return $this->get($id);
    }

    /**
     * 修改菜单
     * @return string
     */
    public function editMenu($param=array(),$id){
        if(count($param)>0){
            $status=$this->edit($id,$param);
            return $status;
        }else{
            return false;
        }
    }
}