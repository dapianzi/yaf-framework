<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/12/8 15:21
 * Created by PhpStorm.
 */
class TaskController extends BaseController {

    public function indexAction($pro=0) {
        $project = (new GanttProjectModel())->get($pro);
        $tasks = (new GanttTaskModel())->getAllTasks($pro);
        // Fn::dump($tasks);
        foreach ($tasks as &$t) {
            $max_end = '';
            foreach ($t['sub_task'] as &$tt) {
                $max_end = max($tt['date_end'], $max_end);
                $tt['date_count'] = ceil((strtotime($tt['date_end']) - strtotime($tt['date_start']))/86400);
                $tt['progress'] = Fn::dateProgress($tt['date_start'], $tt['date_end']);
                $tt['status'] = $tt['progress'] == 100 ? 'success' : '';
            }
            $t['date_end'] = $t['date_end']>$max_end ? $t['date_end'] : $max_end;
            $t['date_count'] = ceil((strtotime($t['date_end']) - strtotime($t['date_start']))/86400);
            $t['progress'] = Fn::dateProgress($t['date_start'], $t['date_end']);
            $t['status'] = $t['progress'] == 100 ? 'info' : '';
        }
        $this->getView()->assign("title", '任务拆分');
        $this->getView()->assign("tasks", $tasks);
        $this->getView()->assign("project", $project);
    }

    public function chartsAction($id=0) {

        $type = $this->_request->getParam('type', 3);
        $first = date('Y-m-d', strtotime('-1 week'));
        if ($type == 2) {
            $first = date('Y-m-01', $first);
        }
        $last = date('Y-m-d', strtotime('-1 day'));
        $today = date('Y-m-d');
        $res = (new GantttaskModel())->gettasks($id);
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
        $this->getView()->assign('title', (new GantttaskModel())->getColumn('SELECT name FROM tasks WHERE id=?', 0, array($id)));
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

    public function addAction() {
        $taskModel = new GanttTaskModel();
        $pid = $this->getRequest()->getParam('pid', 0);
        $pro_id = $this->getRequest()->getParam('pro', 0);
        if (empty($pro_id)) {
            throw new GanttException('Invalid param');
        }
        if ($this->getPost('action') == 'add') {
            $this->_valid_csrf();
            $name = $this->getPost('title', '');
            $desc = $this->getPost('description', '');
            $begin = $this->getPost('date_start', '');
            $end = $this->getPost('date_end', '');

            // validate data
            $info = $taskModel->add(array(
                'title'         => $name,
                'description'   => $desc,
                'pro_id'        => $pro_id,
                'date_start'    => $begin,
                'date_end'      => $end,
                'task_pid'      => $pid,
            ));
            Fn::ajaxSuccess($taskModel->getLastInsertId());
        }
        $task = $taskModel->get($pid);
        $this->getView()->assign('pid', $task['id']);
        $this->getView()->assign('pro', $pro_id);
    }

    public function editAction() {
        $id = $_POST['id'];
        $info = (new GantttaskModel())->getRow('SELECT * FROM gantt_task WHERE id=?', array($id));
        Fn::ajax_success($info);
        return FALSE;

        $id = $_POST['id'];
        $name = $_POST['name'];
        $desc = $_POST['desc'];
        $owner = $_POST['owner'];
        $relate = $_POST['relate'];
        $begin = $_POST['begin'];
        $end = $_POST['end'];
        $order = $_POST['order'];
        $info = (new GantttaskModel())->update('gantt_task', array(
            'name'      => $name,
            'desc'      => $desc,
            'ownner'    => $owner,
            'relation'  => $relate,
            'begin_date'=> $begin,
            'end_date'  => $end,
            'list_order'=> $order,
        ), array('id'=> $id));
        Fn::ajaxSuccess($info);
        return FALSE;
    }

    public function delAction() {
        $id = $_POST['id'];
        $info = (new GantttaskModel())->delete('gantt_task', array('id'=> $id));
        Fn::ajax_success($info);
        return FALSE;
    }

}