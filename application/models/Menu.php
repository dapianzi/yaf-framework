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
    public function getMenu($page=1,$limit=10){
        $star=($page-1)*$limit;
        $menu_db=$this->getAll('select * from menu order by listorder asc limit '.$star.','.$limit);
        $count=$this->getColumn('select count(*) from menu order by listorder asc');
        $menu=array();
        foreach ($menu_db as $rs){
            $menu[]=array(
                'id'=>$rs['id'],
                'icon'=>$rs['icon'],
                'name'=>$rs['name'],
                'url'=>$rs['url'],
                'param'=>$rs['param'],
                'isMenu'=>$rs['isMenu'],
                'isShow'=>$rs['isShow'],
                'status'=>$rs['status']
            );
        }
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$menu);
    }
}