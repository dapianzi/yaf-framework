<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/5/8 17:02
 * Created by PhpStorm.
 */
class GanttProjectModel extends DbModel {

    protected $table = 'project';

    function getProjectTask($id) {
        $sql = " SELECT * FROM project WHERE pro_id=? ORDER BY pid ASC,begin_date ASC ";

        return $this->getAll($sql, array($id));
    }

    function getUserProjectSummary($filter=array()) {
        $sql = "SELECT p.id,p.adate,p.date_from,p.name,p.summary,p.is_public,p.status,p.ownner,";
        $sql.= "MIN(t.task_start)start,MAX(t.task_end)end,SUM(sub_task_count)sub_count,SUM(task_count)task_count ";
        $sql.= "FROM project p LEFT JOIN tasks t ON p.id=t.pro_id ";
        $sql.= "WHERE 1 ";
        $params = array();
        if (!empty($filter)) {
            if (isset($filter['owner'])) {
                $sql.= "AND ownner=? ";
                array_push($params, $filter['owner']);
            }
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
        $sql .= "GROUP BY p.id ";
        return $this->getAll($sql, $params);
    }

    public function getPublicPorjects($filter = array()) {
        $sql = "SELECT p.*,u.nickname FROM project p LEFT JOIN user u ON p.ownner=u.username WHERE is_public=1 ";
        // todo: filter project

        return $this->getAll($sql);
    }
}