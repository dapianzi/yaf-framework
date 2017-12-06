<?php

/**
 *
 * @Author: Carl
 * @Since: 2017-12-06 17:40
 * Created by PhpStorm.
 */
class GanttTaskModel extends DbModel {

    public function getUserJoinTasks($user_id, $end_time='') {
        $sql = "SELECT t.id,t.title,t.date_start,t.date_end,p.name FROM task_user tu ";
        $sql.= "LEFT JOIN task t ON tu.task_id=t.id ";
        $sql.= "LEFT JOIN project p ON t.pro_id=p.id ";
        $sql.= "WHERE tu.user_id=? AND task_end>=? ";

        if (empty($end_time)) {
            $end_time = date('Y-m-d 00:00:00', time()-86400*3);
        }
        $params = array($user_id, $end_time);
        return $this->getAll($sql, $params);
    }

    

}