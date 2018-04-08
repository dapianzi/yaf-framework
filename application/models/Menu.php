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
    public function getMenu($key=array(),$first=0){
        $where='where status=1 ';
        if(is_array($key)&&count($key)>0){
            foreach ($key as $k=>$value){
                if($value!=''){
                    $where.=' and '.$k.' like "%'.$value.'%"';
                }
            }
        }
        if($first==1){
            $where.=' and parentId =0';
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