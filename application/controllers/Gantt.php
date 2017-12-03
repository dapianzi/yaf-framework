<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/5/8 15:21
 * Created by PhpStorm.
 */
class GanttController extends BaseController {

    public function indexAction() {
        $data = (new GanttProjectModel())->getAllProjects();
        $this->getView()->assign("projects", $data);
    }

    public function projectAction($id=0) {
        $last = date('Y-m-d', strtotime('-1 day'));
        $res = (new GanttProjectModel())->getProjects($id);
        $data = array();
        foreach ($res as $v) {
            if ($v['pid'] > 0) {
                $total = $this->task_duration($v['begin_date'], $v['end_date']);
                $v['limit'] = $total/86400;
                if ($total > 0) {
                    $v['progress'] = number_format($this->task_complete($v['begin_date'], $v['end_date'], $last)*100 / $total) . '%';
                } else {
                    $v['progress'] = '--';
                }
                $v['status'] = $v['end_date'] <= $last ? 'finished' : (($v['begin_date']>$last) ? 'prepare' : 'pending');
                $data[$v['pid']]['tasks'][] = $v;
            } else {
                $data[$v['id']] = $v;
            }

        }
        foreach ($data as $k=>&$v) {
            if (isset($v['tasks'])) {
                $total = 0;
                $finish = 0;
                foreach ($v['tasks'] as $task) {
                    $v['end_date'] = max($v['end_date'], $task['end_date']);
                    $total+= $this->task_duration($v['begin_date'], $v['end_date']);
                    $finish+= $this->task_complete($v['begin_date'], $v['end_date'], $last);
                }
                $v['progress'] = $total > 0 ? number_format($finish*100/$total).'%' : '--';
            } else {
                $v['progress'] = strtotime($v['end_date'])>strtotime($v['begin_date'])
                    ? number_format($this->task_complete($v['begin_date'], $v['end_date'], $last)*100 / $this->task_duration($v['begin_date'], $v['end_date'])) . '%'
                    : '--';
            }
            $v['limit'] = (strtotime($v['end_date'])-strtotime($v['begin_date']))/86400;
            $v['status'] = $v['end_date'] <= $last ? 'finished' : (($v['begin_date']>$last) ? 'prepare' : 'pending');
        }
        $this->getView()->assign("id", $id);
        $this->getView()->assign("today", $last);
        $this->getView()->assign("tasks", $data);
        $this->getView()->assign('title', (new GanttProjectModel())->getColumn('SELECT name FROM projects WHERE id=?', 0, array($id)));
    }

    public function chartsAction($id=0) {

        $type = $this->_request->getParam('type', 3);
        $first = date('Y-m-d', strtotime('-1 week'));
        if ($type == 2) {
            $first = date('Y-m-01', $first);
        }
        $last = date('Y-m-d', strtotime('-1 day'));
        $today = date('Y-m-d');
        $res = (new GanttProjectModel())->getProjects($id);
        $tasks = array(
            'start' => date('Y-m-d',strtotime('-1 week')),
            'end'   => date('Y-m-d',strtotime('+1 week')),
            'data'  => array()
        );
        if ($type == 3) {
            $tasks['start'] = date('Y-m-d', strtotime('-2 weeks +1 day'));
        }
        $data = array();
        foreach ($res as $v) {
            if ($v['pid'] > 0) {
                $total = strtotime($v['end_date'])-strtotime($v['begin_date']);
                $v['limit'] = $total/86400;
                $data[$v['pid']]['tasks'][] = $v;
            } else {
                $data[$v['id']] = $v;
            }
        }
        foreach ($data as $k=>&$v) {
            if (isset($v['tasks'])) {
                $total = 0;
                $finish = 0;
                foreach ($v['tasks'] as $task) {
                    $v['end_date'] = max($v['end_date'], $task['end_date']);
                }
            }
            $v['limit'] = (strtotime($v['end_date'].' +1 day')-strtotime($v['begin_date']))/86400;
            if (strtotime($v['begin_date']) > strtotime($last.'+1 week') || strtotime($v['end_date']) < strtotime($first) ) {
                unset($data[$k]);continue;
            }
            if ($type!=3) {
                $tasks['start'] = min($tasks['start'], $v['begin_date']);
            }
            $tasks['end'] = max($v['end_date'], $tasks['end']);
        }
        $html = '';
        $html.= '<tr><td class="name">name</td>';
        $offset = 0;
        for ($i=$tasks['start']; $i<=$tasks['end']; $i=date('Y-m-d', strtotime($i.'+1 day'))) {
            $weekend = $i==$today ? 'today' : ($this->is_holiday($i) ? 'weekend' : '');
            $html.= '<td class="'. $weekend .'">'. date('m-d', strtotime($i)) .'</td>';
            $offset++;
        }
        $html.= '</tr>';
        //Fn::dump($data);
        foreach($data as $t) {
            $html.= $this->render_task_gantt($t, $today, $tasks['start'], $tasks['end']);
            if (isset($t['tasks'])) {
                foreach($t['tasks'] as $tt) {
                    if ($tt['begin_date'] > $tasks['end'] || $tt['end_date'] < $tasks['start']) {
                        continue;
                    }
                    $html.= $this->render_task_gantt($tt, $today, $tasks['start'], $tasks['end'], '----');
                }
            }
        }
        $this->getView()->assign("id", $id);
        $this->getView()->assign("html", $html);
        $this->getView()->assign('title', (new GanttProjectModel())->getColumn('SELECT name FROM projects WHERE id=?', 0, array($id)));
    }

    private function render_task_gantt($t, $d, $s, $e, $pre='') {
        $html = "<tr><td class='name'>{$pre}{$t['name']}</td>";
        for ($i=$s; $i<=$e; $i=date('Y-m-d', strtotime($i.'+1 day'))) {
            $weekend = $i==$d ? 'today' : ($this->is_holiday($i) ? 'weekend' : '');

            if ($t['begin_date'] <= $i && $t['end_date'] >= $i && !$this->is_holiday($i)) {
                if ($pre == '') {
                    $weekend.= ' parent';
                }
                $cls = $i >= $d ? 'undo' : 'done';
                if ($t['begin_date'] == $i) {
                    $cls.= ' first';
                }
                if ($t['end_date'] == $i) {
                    $cls.= ' last';
                }
                $html.= '<td class="'. $weekend . '">'. '<span class="' . $cls .' gantt-bar"></span>' .'</td>';
            } else {
                $html.= '<td class="'. $weekend .'"></td>';
            }
        }
        $html.= '</tr>';
        return $html;
    }

    private function is_holiday($day) {
        $holiday = array(
            '2017-05-29','2017-05-30',
        );
        $workday = array(
            '2017-05-27',
        );
        if (in_array($day, $holiday)) {
            return TRUE;
        }
        if (in_array($day, $workday)) {
            return FALSE;
        }
        return date('w',strtotime($day))==6 || date('w',strtotime($day))==0;
    }

    private function task_complete($s, $e, $cur) {
        if ($cur > $e) {
            $cur = $e;
        }
        if ($cur < $s) {
            return 0;
        }
        return strtotime($cur.'+1 day')-strtotime($s);
    }

    private function task_duration($s, $e) {
        return strtotime($e.'+1 day')-strtotime($s);
    }

    public function AddProjectAction() {
        $pid = $_POST['pid'];
        $pro_id = $_POST['pro_id'];
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $owner = $_POST['owner'];
        $relate = $_POST['relate'];
        $begin = $_POST['begin'];
        $end = $_POST['end'];
        $order = $_POST['order'];

        $info = (new GanttProjectModel())->insert('gantt_project', array(
            'pid'       => $pid,
            'pro_id'    => $pro_id,
            'name'      => $name,
            'desc'      => $desc,
            'ownner'    => $owner,
            'relation'  => $relate,
            'begin_date'=> $begin,
            'end_date'  => $end,
            'list_order'=> $order,
        ));
        Fn::ajax_success($info);
        return FALSE;
    }

    public function ModifyProjectAction() {
        $id = $_POST['id'];
        $info = (new GanttProjectModel())->getRow('SELECT * FROM gantt_project WHERE id=?', array($id));
        Fn::ajax_success($info);
        return FALSE;
    }

    public function UpdateProjectAction() {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $owner = $_POST['owner'];
        $relate = $_POST['relate'];
        $begin = $_POST['begin'];
        $end = $_POST['end'];
        $order = $_POST['order'];
        $info = (new GanttProjectModel())->update('gantt_project', array(
            'name'      => $name,
            'desc'      => $desc,
            'ownner'    => $owner,
            'relation'  => $relate,
            'begin_date'=> $begin,
            'end_date'  => $end,
            'list_order'=> $order,
        ), array('id'=> $id));
        Fn::ajax_success($info);
        return FALSE;
    }

    public function DeleteProjectAction() {
        $id = $_POST['id'];
        $info = (new GanttProjectModel())->delete('gantt_project', array('id'=> $id));
        Fn::ajax_success($info);
        return FALSE;
    }

}