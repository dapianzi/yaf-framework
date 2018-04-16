<?php
/**
 * Created by PhpStorm.
 * User: sky / ouyangdongming
 * Date: 2018/4/3
 * Time: 17:38
 */
class MenuController extends BaseController {

    public function init() {
        parent::init();
        $menu_icon=array('home','set','auz','fire','diamond','location','read','survey','download','component','shezhi1','yinqing','star','chat','list','tubiao','tree','xuanzemoban48','gongju','wenjian','layouts','user','jilu','unlink','senior','tools');
        $this->assign('menu_icon', $menu_icon);
    }

    function indexAction(){

    }

    function listAction(){
        $menu=$this->get_tree(0,'1,2,3');
        if(count($menu)<=0){
            return gf_ajax_error('无菜单');
        }
        return gf_ajax_success($menu,array('count'=>'1'));
    }

    function get_chlid_tree($parentId,$level){
        $MenuModel=new MenuModel();
        $meuninfo=$MenuModel->getMenu($parentId,$level);
        $menu=array();
        foreach ($meuninfo as $rs){
            if($rs['level']==1){
                $name=$rs['name'];
            }elseif($rs['level']==2){
                $name=' &nbsp;├ '.$rs['name'];
            }elseif($rs['level']==3){
                $name=' &nbsp;&nbsp;&nbsp;&nbsp;├ '.$rs['name'];
            }
            $menu_tmp=array(
                'id'=>$rs['id'],
                'listorder'=>$rs['listorder'],
                'icon'=>'<i class="layui-icon layui-icon-'.$rs['icon'].'"></i>',
                'name'=>$name,
                'trname'=>$rs['name'],
                'url'=>$rs['url'],
                'param'=>$rs['param'],
                'isMenu'=>$rs['isMenu'],
                'isShow'=>$rs['isShow'],
                'status'=>$rs['status'],
                'parentId'=>$rs['parentId'],
                'level'=>$rs['level'],
                'items'=>array()
            );

            if($rs['level']==1){
                $menu[$rs['id']]=$menu_tmp;
            }elseif($rs['level']==2){
                $menu[$rs['parentId']]['items'][$rs['id']]=$menu_tmp;
            }elseif($rs['level']==3){
                $menu_tmp_level2=$this->get3level_parentId($meuninfo,$rs['parentId']);
                $menu[$menu_tmp_level2['parentId']]['items'][$rs['parentId']]['items'][$rs['id']]=$menu_tmp;
            }
        }
        return $menu;
    }

    function get3level_parentId($meuninfo,$parentId){
        foreach ($meuninfo as $rs){
            if($parentId==$rs['id']){
                return $rs;
            }
        }
    }

    /**
     * 将菜单生成层级结构
     * @param $menu
     */
    function get_tree($parentId=0,$level=1){
        $menu=$this->get_chlid_tree($parentId,$level);
        $arr2=array();
        foreach ($menu as $key=>$rs){
            $tem_rs=$rs;
            unset($tem_rs['items']);
            $arr2[]=$tem_rs;
            if(count($rs['items'])>0){
                foreach($rs['items'] as $key2=>$val){
                    $tem_rs=$val;
                    unset($tem_rs['items']);
                    $arr2[]=$tem_rs;
                    if(count($val['items'])>0){
                        foreach($val['items'] as $key2=>$val2){
                            //$val['name']='  -- '.$val['name'];
                            unset($val['items']);
                            $arr2[]=$val2;
                        }
                    }
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
        $isName= $this->getQuery('isName',0);
        if(!$type||!$id){
            return gf_ajax_error('参数错误');
        }
        $MenuModel=new MenuModel();
        $status=$MenuModel->changeMenuValue($type,$id,$value);
        if($status){
            $name='';
            if($isName==1){
                $name=$this->getTrueName($id);
            }
            return gf_ajax_success('修改成功',array('name'=>$name));
        }else{
            return gf_ajax_error('修改失败');
        }
    }

    function getTrueName($id){
        $MenuModel=new MenuModel();
        $MenuInfo=$MenuModel->getMenuInfo($id);
        if($MenuInfo['level']==1){
            $name=$MenuInfo['name'];
        }elseif($MenuInfo['level']==2){
            $name=' &nbsp;├ '.$MenuInfo['name'];
        }elseif($MenuInfo['level']==3){
            $name=' &nbsp;&nbsp;&nbsp;&nbsp;├ '.$MenuInfo['name'];
        }
        return array('name'=>$name,'trname'=>$MenuInfo['name']);
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
        if($this->getRequest()->method=='POST'){
            $isMenu=$this->getPost('isMenu')=='on'?'1':'0';
            $isShow=$this->getPost('isShow')=='on'?'1':'0';
            if($this->getPost('parentId')!=0){
                $level=$MenuModel->get_level($this->getPost('parentId'));
            }else{
                $level=1;
            }
            $data=array(
                'parentId'=>$this->getPost('parentId'),
                'name'=>$this->getPost('name'),
                'icon'=>$this->getPost('icon'),
                'url'=>$this->getPost('url'),
                'param'=>$this->getPost('param'),
                'isMenu'=>$isMenu,
                'isShow'=>$isShow,
                'level'=>$level
            );
            $status=$MenuModel->addMenu($data);
            $MenuModel->editMenu(array('listorder'=>$status),$status);
            if($status){
                return gf_ajax_success('添加成功');
            }else{
                return gf_ajax_error('添加失败');
            }
        }else{
            $meuninfo=$this->get_tree('','1,2');
            $parenId=$this->getQuery('parenId');
            $this->assign('menu', $meuninfo);
            $this->assign('parenId', $parenId);
        }
    }



    function editAction(){
        $MenuModel=new MenuModel();
        if($this->getRequest()->method=='POST'){
            $idx=$this->getPost('id');
            $isMenu=$this->getPost('isMenu')=='on'?'1':'0';
            $isShow=$this->getPost('isShow')=='on'?'1':'0';
            if($this->getPost('parentId')!=0){
                $level=$MenuModel->get_level($this->getPost('parentId'));
            }else{
                $level=1;
            }
            $data=array(
                'parentId'=>$this->getPost('parentId'),
                'name'=>$this->getPost('name'),
                'icon'=>$this->getPost('icon'),
                'url'=>$this->getPost('url'),
                'param'=>$this->getPost('param'),
                'isMenu'=>$isMenu,
                'isShow'=>$isShow,
                'level'=>$level
            );
            $status=$MenuModel->editMenu($data,$idx);
            if($status){
                return gf_ajax_success('修改成功');
            }else{
                return gf_ajax_error('修改失败');
            }
        }else{
            $id=$this->getQuery('id');
            if(!$id){
                throw new SysException('ID参数不存在');
            }
            $meuninfo=$this->get_tree('','1,2');
            $MenuInfo=$MenuModel->getMenuInfo($id);
            $this->assign('menu', $meuninfo);
            $this->assign('MenuInfo', $MenuInfo);
        }
    }
}