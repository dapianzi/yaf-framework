<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/5/8 17:02
 * Created by PhpStorm.
 */
class GanttProjectModel extends DbModel {

    public $tableName = 'gantt_project';

    function __construct($confName='mysql') {
        parent::__construct($confName);
    }

    function getProjects($id) {
        $sql = " SELECT * FROM {$this->tableName} WHERE pro_id=? ORDER BY pid ASC,begin_date ASC ";
        return $this->getAll($sql, array($id));
    }

    function getAllProjects() {
        return $this->getAll(' SELECT * FROM projects ');
    }

}