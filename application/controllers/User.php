<?php
/**
 * Created by PhpStorm.
 * User: sky / ouyangdongming
 * Date: 2018/4/8
 * Time: 15:25
 */
class UserController extends BaseController {

    public function groupAction(){

    }

    public function grouplistAction(){
        $page=$this->getQuery('page',1);
        $limit=$this->getQuery('limit',20);
        $RoleModel=new RoleModel();
        $UserGroup=$RoleModel->getUserGroup($page,$limit);
        if(count($UserGroup['count'])<=0){
            return gf_ajax_error('无用户组角色');
        }
        return gf_ajax_success($UserGroup['userGroup'],array('count'=>$UserGroup['count']));
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
        if($type=='status'&&$value=='true'){
            $value=1;
        }elseif($type=='status'&&$value=='false'){
            $value=0;
        }
        $RoleModel=new RoleModel();
        $status=$RoleModel->changeUserGroupValue($type,$id,$value);
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
        $RoleModel=new RoleModel();
        $status=$RoleModel->delUserGroup($id);
        if($status){
            return gf_ajax_success('修改成功');
        }else{
            return gf_ajax_error('修改失败');
        }
    }

    function addAction(){
        $RoleModel=new RoleModel();
        if($this->getRequest()->method=='POST'){
            $status=$this->getPost('status')=='on'?'1':'0';
            $data=array(
                'name'=>$this->getPost('name'),
                'description'=>$this->getPost('description'),
                'status'=>$status,
            );
            $status=$RoleModel->addUserGroup($data);
            if($status){
                return gf_ajax_success('添加成功');
            }else{
                return gf_ajax_error('添加失败');
            }
        }
    }


    function authAction(){
        $menu=$this->get_chlid_tree(0,'1,2,3');
        $this->assign('menu',$menu);
    }

    function get_chlid_tree($parentId,$level){
        $MenuModel=new MenuModel();
        $meuninfo=$MenuModel->getMenu($parentId,$level);
        $menu=array();
        foreach ($meuninfo as $rs){
            $menu_tmp=array(
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
}