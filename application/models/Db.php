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

    public function __construct($table = '') {
        // 这里初始化传入table名，可以一个数据库Model实例化多个table.
        $confName = !empty($this->confName) ? $this->confName : 'mysql';
        $conf = Yaf_Registry::get('config')->$confName;
        parent::__construct($conf->dsn, $conf->username, $conf->password);
        $this->table = empty($table) ? $this->table : $table;
        if (empty($this->table)) {
            $this->table = strtolower(str_replace('Model', '', get_class($this)));
        }
    }

    public function get($id) {
        return $this->getRow("SELECT * FROM {$this->table} WHERE {$this->pk}=?", array($id));
    }

    /**
     * @param string $col
     * @param string $table
     * 查询表的字段
     * @return mixed
     */
    public function getColInfo($col, $table='') {
        if (empty($table)) {
            $table = $this->table;
        }
        return $this->getRow("SHOW COLUMNS FROM {$table} WHERE FIELD LIKE ?", array($col));
    }

    /**
     * @param string $col
     * @param string $table
     * 快速获取枚举类型列表
     * @return array
     */
    public function getColEnum($col, $table='') {
        $col_info = $this->getColInfo($col, $table);
        $enum = explode(',', preg_replace('/^enum\((.*)\)$/i', '$1', $col_info['Type']));
        return array_map(function($v){return trim($v, '\'');}, $enum);
    }

    public function add($data) {
        return $this->insert($this->table, $data);
    }

    public function edit($id, $data){
        return $this->mod($id, $data);
    }

    public function mod($id, $data) {
        return $this->update($this->table, $data, array($this->pk => $id));
    }

    public function set($ids, $field, $value) {
        return $this->update($this->table, array($field=>$value), array($this->pk => $ids));
    }

    public function del($ids) {
        return $this->delete($this->table, array($this->pk => $ids));
    }
}