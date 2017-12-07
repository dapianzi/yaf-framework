<?php
/**
 * @name IndexController
 * @author Carl
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/sample/index/index/index/name/KF 的时候, 你就会发现不同
     */
	public function indexAction() {
		$proModel = new GanttProjectModel();
		$myProjects = $proModel->getUserProjectSummary($this->gantt_user['username']);
		foreach ($myProjects as &$p) {
		    $p['process'] = $this->projectProcess($p['status'], $p['start'], $p['end']);
        }
		$this->getView()->assign('title', '我的项目列表');
		$this->getView()->assign('projects', $myProjects);
	}


	public function infoAction($id) {

    }

	public function addProjectAction() {
        if ($this->getPost('action') == 'add') {
            $name = $this->getPost('pro_name');
            $start = $this->getPost('pro_start');
            $summary = $this->getPost('pro_summary');
            $is_public = $this->getPost('is_public', 0);
            $ownner = $this->gantt_user['username'];

            $res = (new GanttProjectModel())->add(array(
                'name' => $name,
                'date_from' => $start,
                'summary' => $summary,
                'ownner' => $ownner,
                'is_public' => $is_public,
                'status' => 0,
            ));
            if ($res) {
                Fn::ajaxSuccess($res);
            } else {
                Fn::ajaxError('insert failed.');
            }
        }
        $this->assign('action', 'add');
	}

	public function editProjectAction() {
        $id = $this->getRequest()->getParam('id', 0);
        $proModel = new GanttProjectModel();
        if ($this->getPost('action') == 'edit') {
            $name = $this->getPost('pro_name');
            $start = $this->getPost('pro_start');
            $summary = $this->getPost('pro_summary');
            $is_public = $this->getPost('is_public', 0);

            $res = $proModel->edit($id, array(
                'name' => $name,
                'date_from' => $start,
                'summary' => $summary,
                'is_public' => $is_public,
            ));
            if ($res) {
                Fn::ajaxSuccess($res);
            } else {
                Fn::ajaxError('insert failed.');
            }
        }
        $pro_info = $proModel->getProjectInfo($id);
        $this->getView()->assign('info', $pro_info);
	}

	public function delProjectAction() {
        $ids = $this->getPost('ids', 0);
        $proModel = new GanttProjectModel();
        $res = $proModel->del($ids);
        Fn::ajaxSuccess($res);
	}

	public function togglePublicAction() {
        $id = $this->getRequest()->getParam('id', 0);
        $proModel = new GanttProjectModel();
        $is_public = $this->getPost('is_public');

        $res = $proModel->set($id, 'is_public', $is_public);
        Fn::ajaxSuccess($res);
    }

	public function setStatusAction() {
        $ids = $this->getParam('ids', 0);
        $proModel = new GanttProjectModel();
        $status = $this->getPost('status');

        $res = $proModel->set($ids, 'status', $status);
        Fn::ajaxSuccess($res);
    }

	private function projectProcess($status, $start, $end) {
        if ($status == -1) {
            return '<span class="label label-warning">已放弃</span>';
        } else if ($status == 1) {
            return '<span class="label label-success">已完成（100%）</span>';
        } else {
            $now = time();
            $start = strtotime($start);
            $end = strtotime($end);
            if ($now >= $end) {
                $process = 99.99;
            } else if ($now <= $start) {
                $process = 0;
            } else {
                $process = round(($now-$start)*100/($end-$start), 2);
            }
            return '<span class="label label-primary">进行中（'.$process.'%）</span>';
        }
    }

}
