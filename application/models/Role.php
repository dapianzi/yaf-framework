<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:54
 */

class RoleModel extends PDOModel {

    public $table = 'roles';

    public function validPermission($node_id, $role_id) {
        $perm = $this->getPermissions($role_id);
        if (isset($perm['denied'])) {
            return !in_array($node_id, $perm['denied']);
        } else {
            return in_array($node_id, $perm['access']);
        }
    }

    public function getPermissions($role_id) {
        $perm = $this->getColumn("SELECT permissions FROM roles WHERE id=?", [$role_id]);
        if ($perm) {
            return json_decode($perm, TRUE);
        }
        return ['access'=> []];
    }

}