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
		    $p['progress'] = $this->projectProgress($p);
        }
		$this->getView()->assign('title', '我的项目列表');
		$this->getView()->assign('projects', $myProjects);
	}

	public function addprojectAction() {
        if ($this->getPost('action') == 'add') {
            $name = $this->getPost('pro_name');
            $start = $this->getPost('from_date');
            $summary = $this->getPost('pro_summary');
            $is_public = $this->getPost('is_public', 0);
            $ownner = $this->gantt_user['username'];
            if (empty($start)) {
                $start = date('Y-m-d');
            }
            $this->_valid_csrf();
            $proModel = new GanttProjectModel();
            $res = $proModel->add(array(
                'name' => $name,
                'date_from' => $start,
                'summary' => $summary,
                'ownner' => $ownner,
                'is_public' => $is_public,
                'status' => 0,
            ));
            if ($res) {
                Fn::ajaxSuccess($this->base_uri.'/task/index/id/'.$proModel->getLastInsertId());
            } else {
                Fn::ajaxError('insert failed.');
            }
        }
        $this->getView()->assign('action', 'add');
	}

	public function editprojectAction() {
	    $id = $this->getParam('id', 0);
        $proModel = new GanttProjectModel();
        if ($this->getPost('action') == 'edit') {
            $name = $this->getPost('pro_name');
            $start = $this->getPost('from_date');
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
        $pro_info = $proModel->get($id);
        $this->getView()->assign('pro', $pro_info);
        $this->getView()->assign('action', 'edit');
	}

	public function modProjectAction() {
        $ids = $this->getPost('ids', 0);
        $act = $this->getPost('action', '');
        $proModel = new GanttProjectModel();
        switch ($act) {
            case 'del':
                $res = $proModel->del($ids);
                break;
            case 'finish':
                $res = $proModel->set($ids, 'status', 1);
                break;
            case 'abandon':
                $res = $proModel->set($ids, 'status', -1);
                break;
            default:

        }
        $this->redirect($this->base_uri);exit;
	}

	public function togglePublicAction() {
        $id = $this->getRequest()->getParam('id', 0);
        $proModel = new GanttProjectModel();
        $is_public = $this->getPost('is_public');

        $res = $proModel->set($id, 'is_public', $is_public);
        Fn::ajaxSuccess($res);
    }

	private function projectProgress($pro) {
        if ($pro['status'] == -1) {
            return -1;
        } elseif ($pro['status'] == 1) {
            return 100;
        } elseif ($pro['task_count'] == 0) {
            return 0;
        } else {
            $now = time();
            $start = strtotime($pro['start']);
            $end = strtotime($pro['end']);
            if ($now >= $end) {
                $progress = 99;
            } else if ($now <= $start) {
                $progress = 0;
            } else {
                $progress = floor(($now-$start)*100/($end-$start));
            }
            return $progress;
        }
    }

}
