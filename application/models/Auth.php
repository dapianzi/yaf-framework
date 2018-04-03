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

    /**
     * 判断当前权限
     * @param array $userinfo
     * @param string $node
     * @return bool
     */
    public function getCurrentAuth($userinfo,$node){
        $conf = Yaf_Registry::get('config');
        $no_auth_node=$conf->application->no_auth_node;
        $roleId=$userinfo['roleId'];
        if($roleId!=1){
            $node_id=$this->getColumn("SELECT id FROM menu where url = '".$node."'");
            $authority=$this->getColumn("SELECT authority FROM role where id = ".$roleId);
            $authority_arr=explode(',',$authority);
            $no_auth_node_arr=explode(',',$no_auth_node);
            if((is_array($authority_arr)&&in_array($node_id,$authority_arr))||in_array($node,$no_auth_node_arr)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
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