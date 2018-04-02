<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/4/11 15:54
 * Created by PhpStorm.
 */
class DbModel extends DbClass
{
    // table name
    public $table = '';
    // primary key
    public $pk = 'id';

    public $confName = 'mysql';

    public function __construct($confName = '') {

        $confName = empty($confName) ? $this->confName : $confName;
        $conf = Yaf_Registry::get('config')->$confName;
        parent::__construct($conf->dsn, $conf->username, $conf->password);

        if (empty($this->table)) {
            $this->table = strtolower(str_replace('Model', '', get_class($this)));
        }
    }

    public function get($id) {
        return $this->getRow("SELECT * FROM {$this->table} WHERE {$this->pk}=?", array($id));
    }

    public function add($data) {
        return $this->insert($this->table, $data);
    }

    public function edit($id, $data) {
        return $this->update($this->table, $data, array($this->pk => $id));
    }

    public function set($ids, $field, $value) {
        return $this->update($this->table, array($field=>$value), array($this->pk => $ids));
    }

    public function del($ids) {
        return $this->delete($this->table, array($this->pk => $ids));
    }
}