<?php

/**
 *
 * @Author: Carl
 * @Since: 2018-04-04 15:27
 * Created by PhpStorm.
 */
class ScriptTemplateModel extends DbModel {
    public $table = 'script_template';

    public function getTemplateByName($name) {
        $sql = "SELECT * FROM {$this->table} WHERE name=?";
        return $this->getRow($sql, array($name));
    }
}