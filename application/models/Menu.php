<?php
/**
 * Created by PhpStorm.
 * User: sky / ouyangdongming
 * Date: 2018/4/3
 * Time: 13:59
 */
class MenuModel extends PDOModel {

    /**
     * 获取菜单
     * return array
     */
    public function getMenuList($role_id) {
        $roleModel = new RoleModel();
        $permissions = $roleModel->getPermissions($role_id);
        if (isset($permissions['denied'])) {
            $ids = $permissions['denied'];
            $where = empty($ids) ? '1=1' : ' id NOT IN ('. $this->questionMarks(count($ids)) .') ';
        } else {
            $ids = $permissions['access'];
            $where = empty($ids) ? '1=0' : ' id IN ('. $this->questionMarks(count($ids)) .') ';
        }
        $menus = $this->getAll('SELECT * FROM menu WHERE status=0 AND is_show=1 AND '.$where.' order by pid ASC,list_order ASC ', $ids);
        return $this->_assocByPid($menus);
    }

    /**
     * 获取菜单
     * return array
     */
    public function getAllMenuList($field = '*') {
        $menus = $this->getAll('SELECT ' . $field . ' FROM menu order by pid ASC,list_order ASC ');
        return $this->_assocByPid($menus);
    }

    private function _assocByPid($menus) {
        $menu = [];
        foreach ($menus as $id => $m) {
            $menu[$m['pid']][] = $m;
        }
        return $menu;
    }

    /**
     * @param $node
     * @return mixed
     */
    public function getNodeName($node){
        $sql = 'SELECT n.node,n.href,p.node p_node,p.href p_href FROM menu n LEFT JOIN menu p ON n.pid=p.id '
            .' WHERE FIND_IN_SET(?, n.perm_route)';
        return $this->getRow($sql, array($node));
    }

    /**
     * @param $url
     * @return mixed
     */
    public function getMenuId($url) {
        return $this->getColumn("SELECT id FROM menu WHERE FIND_IN_SET(?, perm_route)", array($url));
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




}