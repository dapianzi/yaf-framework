<?php

/**
 *
 * @Author: Carl
 * @Since: 2017-12-06 17:40
 * Created by PhpStorm.
 */
class GanttTaskModel extends DbModel {

    protected $table = 'task';

    public function getUserJoinTasks($user_id, $end_time='') {
        $sql = "SELECT t.id,t.title,t.date_start,t.date_end,p.name FROM task_user tu ";
        $sql.= "LEFT JOIN task t ON tu.task_id=t.id ";
        $sql.= "LEFT JOIN project p ON t.pro_id=p.id ";
        $sql.= "WHERE tu.user_id=? AND task_end>=? ";

        if (empty($end_time)) {
            $end_time = date('Y-m-d', time()-86400*3);
        }
        $params = array($user_id, $end_time);
        return $this->getAll($sql, $params);
    }

    public function getAllTasks($pro_id, $from='') {
        $from = empty($from) ? date('Y-m-d', time()-30*86400) : $from;
        $sql = "SELECT t.* FROM subtasks t LEFT JOIN project p ON t.pro_id=p.id ";
        $sql.= "WHERE p.id=? AND date_start>=? ORDER BY date_start ASC,sub_task_date_start ASC ";
        $tasks = $this->getAll($sql, array($pro_id, $from));
        $ret = array();
        foreach ($tasks as $t) {
            if (!isset($ret[$t['id']])) {
                $ret[$t['id']] = array(
                    'id' => $t['id'],
                    'adate' => $t['adate'],
                    'title' => $t['title'],
                    'description' => $t['description'],
                    'date_start' => $t['date_start'],
                    'date_end' => $t['date_end'],
                    'sub_task' => array(),
                );
            }
            if (!empty($t['sub_task_id'])) {
                $ret[$t['id']]['sub_task'][] = array(
                    'pid' => $t['task_pid'],
                    'id' => $t['sub_task_id'],
                    'adate' => $t['sub_task_adate'],
                    'title' => $t['sub_task_title'],
                    'description' => $t['sub_task_description'],
                    'date_start' => $t['sub_task_date_start'],
                    'date_end' => $t['sub_task_date_end'],
                );
            }
        }
        return $ret;
    }

    public function getTaskInfo($id) {
        $sql = "SELECT t.*,p.name pro_name,pt.title pt_title FROM task t LEFT JOIN task pt ON t.task_pid=pt.id ";
        $sql.= "LEFT JOIN project p ON t.pro_id=p.id WHERE t.id=?";
        return $this->getRow($sql, array($id));
    }

}