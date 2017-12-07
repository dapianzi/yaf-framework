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

	}

	public function editProjectAction() {

	}

	public function delProjectAction() {

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
