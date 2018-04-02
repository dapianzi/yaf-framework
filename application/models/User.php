<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:54
 */

class UserModel extends DbModel {

    public function getUserInfo($username) {
        return $this->getRow("SELECT * FROM {$this->table} WHERE name=?", array($username));
    }
}