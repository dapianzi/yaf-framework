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
            $t['status'] = $t['progress'] == 100 ? 'info' : 'warning';
        }
        $this->getView()->assign("title", '任务拆分');
        $this->getView()->assign("tasks", $tasks);
        $this->getView()->assign("project", $project);
    }

    public function ganttAction()
    {
        $pro = $this->getParam('pro', 0);
        $project = (new GanttProjectModel())->get($pro);
        $this->getView()->assign('project', $project);
        $tasks = (new GanttTaskModel())->getAllTasks($pro, date('Y-m-d', time()-180*86400));
        // Fn::dump($tasks);
        $date_s = date('Y-m-d', time()-7*86400);
        $date_e = date('Y-m-d', time()+21*86400);
        foreach ($tasks as &$task) {
            $max_end = date('Y-m-d');
            foreach ($task['sub_task'] as &$sub_task) {
                $max_end = max($sub_task['date_end'], $max_end);
            }
            $task['date_end'] = $task['date_end'] > $max_end ? $task['date_end'] : $max_end;
            $date_s = $date_s<$task['date_start'] ? $date_s : $task['date_start'];
            $date_e = $date_e>$task['date_end'] ? $date_e : $task['date_end'];
        }

        $gantt_data = array(
            'date' => array(),
            'tasks' => array(),
            'pre' => array(),
            'done' => array(),
            'today' => array(),
            'todo' => array(),
            'after' => array(),
        );
        $today = date('Y-m-d');
        foreach ($tasks as $t) {
            array_unshift($gantt_data['tasks'], $t['title']);
            $task_time = $this->_task_time($date_s, $date_e, $t['date_start'], $t['date_end'], $today);
            array_unshift($gantt_data['pre'], $task_time[0]);
            array_unshift($gantt_data['done'], $task_time[1]);
            array_unshift($gantt_data['today'], $task_time[2]);
            array_unshift($gantt_data['todo'], $task_time[3]);
            array_unshift($gantt_data['after'], $task_time[4]);
            foreach ($t['sub_task'] as $tt) {
                array_unshift($gantt_data['tasks'], '----'.$tt['title']);
                $task_time = $this->_task_time($date_s, $date_e, $tt['date_start'], $tt['date_end'], $today);
                array_unshift($gantt_data['pre'], $task_time[0]);
                array_unshift($gantt_data['done'], $task_time[1]);
                array_unshift($gantt_data['today'], $task_time[2]);
                array_unshift($gantt_data['todo'], $task_time[3]);
                array_unshift($gantt_data['after'], $task_time[4]);
            }
        }
        $this->getView()->assign('chart_height', count($gantt_data['tasks'])*36+60);
        $this->getView()->assign('date_s', strtotime($date_s)*1000);
        $this->getView()->assign('today', (strtotime($today) - strtotime($date_s))/86400);
        $this->getView()->assign('gantt_data', json_encode($gantt_data));
    }

    private function _task_time($s, $e, $ts, $te, $t) {
        $pre=$done=$today=$todo=$after = 0;
        if ($ts > $t) {
            $pre = (strtotime($ts) - strtotime($s))/86400 + 1;
            $todo = (strtotime($te) - strtotime($ts))/86400;
            $after = (strtotime($e) - strtotime($te))/86400;
        } else if ($te < $t) {
            $pre = (strtotime($ts) - strtotime($s))/86400;
            $done = (strtotime($te) - strtotime($ts))/86400;
            $after = (strtotime($e) - strtotime($te))/86400 + 1;
        } else {
            $pre = (strtotime($ts) - strtotime($s))/86400;
            $done = (strtotime($t) - strtotime($ts))/86400;
            $today = 1;
            $todo = (strtotime($te) - strtotime($t))/86400;
            $after = (strtotime($e) - strtotime($te))/86400;
        }

        return array($pre, $done, $today, $todo, $after);
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
        $pid = $this->getParam('pid', 0);
        $pro_id = $this->getParam('pro', 0);
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
        $project = (new GanttProjectModel())->get($pro_id);
        $this->getView()->assign('ptask', $task);
        $this->getView()->assign('project', $project);
    }

    public function editAction() {
        $taskModel = new GanttTaskModel();
        $id = $this->getParam('id', 0);
        if (empty($id)) {
            throw new GanttException('Invalid param');
        }
        if ($this->getPost('action') == 'edit') {
            $this->_valid_csrf();
            $name = $this->getPost('title', '');
            $desc = $this->getPost('description', '');
            $begin = $this->getPost('date_start', '');
            $end = $this->getPost('date_end', '');

            // validate data
            $info = $taskModel->edit($id, array(
                'title'         => $name,
                'description'   => $desc,
                'date_start'    => $begin,
                'date_end'      => $end,
            ));
            Fn::ajaxSuccess($taskModel->getLastInsertId());
        }
        $task = $taskModel->getTaskInfo($id);
        if (empty($task)) {
            throw new GanttException('Invalid param');
        }
        $this->getView()->assign('task', $task);
    }

    public function delAction() {
        $this->_valid_csrf();
        $ids = $this->getParam('ids', 0);
        $info = (new GantttaskModel())->del($ids);
        Fn::ajaxSuccess($info);
    }

}