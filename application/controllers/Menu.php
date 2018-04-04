<?php
/**
 * Created by PhpStorm.
 * User: sky / ouyangdongming
 * Date: 2018/4/3
 * Time: 17:38
 */
class MenuController extends BaseController {

    function indexAction(){

    }

    function listAction(){
        $page= $this->getQuery('page');
        $limit= $this->getQuery('limit');
        $key= $this->getQuery('key');
        $MenuModel=new MenuModel();
        $meuninfo=$MenuModel->getMenu($key);
        $count=$meuninfo['count'];
        $menu_db=$meuninfo['menu'];
        $menu=array();
        if($count<=0){
            return gf_ajax_error('无菜单');
        }
        foreach ($menu_db as $rs){
            //$isMenu=$rs['isMenu']==1?'<i class="layui-icon" style="font-size: 20px; color: #5FB878;">&#x1005;</i>':'<i class="layui-icon" style="font-size: 20px; color: #FF5722;">&#x1007;</i>';
            //$isShow=$rs['isShow']==1?'<i class="layui-icon" style="font-size: 20px; color: #5FB878;">&#x1005;</i>':'<i class="layui-icon" style="font-size: 20px; color: #FF5722;">&#x1007;</i>';
            //$status=$rs['status']==1?'<i class="layui-icon" style="font-size: 20px; color: #5FB878;">&#x1005;</i>':'<i class="layui-icon" style="font-size: 20px; color: #FF5722;">&#x1007;</i>';
            if($rs['parentId']==0){
                $menu[$rs['id']]=array(
                    'id'=>$rs['id'],
                    'listorder'=>$rs['listorder'],
                    'icon'=>'<i class="layui-icon layui-icon-'.$rs['icon'].'"></i>',
                    'name'=>$rs['name'],
                    'url'=>$rs['url'],
                    'param'=>$rs['param'],
                    'isMenu'=>$rs['isMenu'],
                    'isShow'=>$rs['isShow'],
                    'status'=>$rs['status'],
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
                    'isMenu'=>$rs['isMenu'],
                    'isShow'=>$rs['isShow'],
                    'status'=>$rs['status'],
                    'parentId'=>$rs['parentId'],
                );
            }
        }
        $menu=$this->get_tree($menu);
        if(count($menu)<=0){
            return gf_ajax_error('无菜单');
        }
        return gf_ajax_success($menu,array('count'=>$count));
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
                    //$val['name']='  -- '.$val['name'];
                    $arr2[]=$val;
                }
            }
        }
        return $arr2;
    }

    /**
     * 更改菜单显示隐藏
     */
    function switchAction(){
        $type= $this->getQuery('type');
        $id= $this->getQuery('id');
        $value= $this->getQuery('value');
        if(!$type||!$id||!$value){
            return gf_ajax_error('参数错误');
        }
        if($value=='true'){
            $value=1;
        }else{
            $value=0;
        }
        $MenuModel=new MenuModel();

        $status=$MenuModel->changeMenuValue($type,$id,$value);
        if($status){
            return gf_ajax_success('修改成功');
        }else{
            return gf_ajax_error('修改失败');
        }
    }

    /**
     * ajax修改菜单字段
     */
    function changedataAction(){
        $type= $this->getQuery('type');
        $id= $this->getQuery('id');
        $value= $this->getQuery('value');
        if(!$type||!$id){
            return gf_ajax_error('参数错误');
        }
        $MenuModel=new MenuModel();

        $status=$MenuModel->changeMenuValue($type,$id,$value);
        if($status){
            return gf_ajax_success('修改成功');
        }else{
            return gf_ajax_error('修改失败');
        }
    }

    /**
     * 删除
     */
    function delAction(){
        $id= $this->getQuery('id');
        if(!$id){
            return gf_ajax_error('参数错误');
        }
        $MenuModel=new MenuModel();
        $status=$MenuModel->delMenu($id);
        if($status){
            return gf_ajax_success('修改成功');
        }else{
            return gf_ajax_error('修改失败');
        }
    }

    /**
     * 批量删除
     */

    function deleteAction(){
        $idx= $this->getQuery('idx');
        if(!$idx){
            return gf_ajax_error('参数错误');
        }
        $ids=explode(',',$idx);
        $MenuModel=new MenuModel();
        if(count($ids)){
            foreach ($ids as $id){
                $status=$MenuModel->delMenu($id);
            }
        }
        return gf_ajax_success('修改成功');

    }

    function addAction(){
        $MenuModel=new MenuModel();
        $meuninfo=$MenuModel->getMenu(array(),1);
        $menu_db=$meuninfo['menu'];
        $this->assign('menu', $menu_db);
    }
}