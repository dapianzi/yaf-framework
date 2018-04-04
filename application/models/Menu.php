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
                    $where.=' and '.$k.'="'.$value.'"';
                }
            }
        }
        $menu_db=$this->getAll('select * from menu '.$where.' order by listorder asc,id asc');
        $count=$this->getColumn('select count(*) from menu '.$where);
        $menu=array();
        if($count<=0){
            return array('code'=>-1,'msg'=>'无菜单','count'=>$count,'data'=>$menu);
        }
        foreach ($menu_db as $rs){
            $isMenu=$rs['isMenu']==1?'<i class="layui-icon" style="font-size: 20px; color: #5FB878;">&#x1005;</i>':'<i class="layui-icon" style="font-size: 20px; color: #FF5722;">&#x1007;</i>';
            $isShow=$rs['isShow']==1?'<i class="layui-icon" style="font-size: 20px; color: #5FB878;">&#x1005;</i>':'<i class="layui-icon" style="font-size: 20px; color: #FF5722;">&#x1007;</i>';
            $status=$rs['status']==1?'<i class="layui-icon" style="font-size: 20px; color: #5FB878;">&#x1005;</i>':'<i class="layui-icon" style="font-size: 20px; color: #FF5722;">&#x1007;</i>';
            if($rs['parentId']==0){
                $menu[$rs['id']]=array(
                    'id'=>$rs['id'],
                    'listorder'=>$rs['listorder'],
                    'icon'=>'<i class="layui-icon layui-icon-'.$rs['icon'].'"></i>',
                    'name'=>$rs['name'],
                    'url'=>$rs['url'],
                    'param'=>$rs['param'],
                    'isMenu'=>$isMenu,
                    'isShow'=>$isShow,
                    'status'=>$status,
                    'parentId'=>$rs['parentId'],
                    'items'=>array(),
                );
            }else{
                $menu[$rs['parentId']]['items'][$rs['id']]=array(
                    'id'=>$rs['id'],
                    'listorder'=>$rs['listorder'],
                    'icon'=>'<i class="layui-icon layui-icon-'.$rs['icon'].'"></i>',
                    'name'=>$rs['name'],
                    'url'=>$rs['url'],
                    'param'=>$rs['param'],
                    'isMenu'=>$isMenu,
                    'isShow'=>$isShow,
                    'status'=>$status,
                    'parentId'=>$rs['parentId'],
                );
            }
        }
        $menu=$this->get_tree($menu);
        if(count($menu)<=0){
            return array('code'=>-1,'msg'=>'无菜单','count'=>$count,'data'=>$menu);
        }
        return array('code'=>0,'msg'=>'','count'=>$count,'data'=>$menu);
    }

    /**
     * 将菜单生成层级结构
     * @param $menu
     */
    function get_tree($menu){
        $arr2=array();
        foreach ($menu as $key=>$rs){
            $tem_rs=$rs;
            unset($tem_rs['items']);
            $arr2[]=$tem_rs;
            if(count($rs['items'])>0){
                foreach($rs['items'] as $key2=>$val){
                    $val['name']='  -- '.$val['name'];
                    $arr2[]=$val;
                }
            }
        }
        return $arr2;
    }
}