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
    public function getMenu($key){
        $where='where status=1 ';
        if(is_array($key)&&count($key)>0){
            foreach ($key as $k=>$value){
                if($value!=''){
                    $where.=' and '.$k.' like "%'.$value.'%"';
                }
            }
        }
        $menu_db=$this->getAll('select * from menu '.$where.' order by listorder asc,id asc');
        $count=$this->getColumn('select count(*) from menu '.$where);
        return array('count'=>$count,'menu'=>$menu_db);
    }

    public function changeMenuValue($field,$id,$value){
        $status=$this->set($id,$field,$value);
        return $status;
    }

    public function delMenu($id){
        $status=$this->del($id);
        return $status;
    }
}