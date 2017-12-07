<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/5/8 17:02
 * Created by PhpStorm.
 */
class GanttProjectModel extends DbModel {

    function getProjectTask($id) {
        $sql = " SELECT * FROM project WHERE pro_id=? ORDER BY pid ASC,begin_date ASC ";

        return $this->getAll($sql, array($id));
    }

    function getUserProjectSummary($user, $filter=array()) {
        $sql = "SELECT p.id,p.adate,p.date_from,p.name,p.summary,p.is_public,p.status,";
        $sql.= "MIN(t.task_start)start,MAX(t.task_end)end,SUM(sub_task_count)sub_count,SUM(task_count)task_count ";
        $sql.= "FROM project p LEFT JOIN tasks t ON p.id=t.pro_id ";
        $sql.= "WHERE ownner=? GROUP BY p.id ";
        $params = array($user);
        if (!empty($filter)) {
            if (isset($filter['status'])) {
                $sql.= "AND status=? ";
                array_push($params, $filter['status']);
            }
            if (isset($filter['public'])) {
                $sql.= "AND is_public=? ";
                array_push($params, $filter['public']);
            }
            if (isset($filter['name'])) {
                $sql.= "AND name LIKE ? ";
                array_push($params, '%'.$filter['name'].'%');
            }
            if (isset($filter['date_from'])) {
                if (isset($filter['date_from']['s'])){
                    $sql.= "AND date_from>=? ";
                    array_push($params, $filter['date_from']['s']);
                }
                if (isset($filter['date_from']['e'])){
                    $sql.= "AND date_from<=? ";
                    array_push($params, $filter['date_from']['e']);
                }
            }
        }
        return $this->getAll($sql, $params);
    }

}