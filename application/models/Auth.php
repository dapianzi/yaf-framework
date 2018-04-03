<?php
/**
 * Created by PhpStorm.
 * User: sky / ouyangdongming
 * Date: 2018/4/3
 * Time: 13:59
 */
class AuthModel extends DbModel {
    // table name
    public $table = 'menu';

    public $confName = 'mysql';

    public function getCurrentAuth($userinfo,$node){
        $auth=$this->getUserAuth($userinfo);
    }

    /**
     * 获取用户权限
     * @param int $userId 用户ID
     * return array
     */
    public function getUserAuth($userInfo){
        $roleId=$userInfo['roleId'];
        if($roleId==1){
            $sql='select id,parentId,childIds,name,icon,url,param from menu where status=1 AND isMenu=1 and isShow=1 order by listorder asc';
        }else{
            $sql='select id,parentId,childIds,name,icon,url,param from menu where status=1 AND isMenu=1 and isShow=1 and instr((select authority from role where id='.$roleId.'),id)>0 order by listorder asc;';
        }
        $auth=$this->getAll($sql);
        $menu=array();
        foreach ($auth as $rs){
            if($rs['parentId']==0){
                $menu[$rs['id']]=array(
                    'page' =>$rs['name'],
                    'href' => $rs['url'].$rs['param'],
                    'icon' => $rs['icon']
                );
            }else{
                $menu[$rs['parentId']]['items'][$rs['name']]=$rs['url'].$rs['param'];
            }

        }
        return $menu;
    }
}