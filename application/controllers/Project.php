<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/12 0012
 * Time: 21:56
 */

class ProjectController extends BaseController
{
    public function indexAction() {
        // todo: handle fitler params
        $filter = array(
            'public' => 1,
        );
        $projectModel = new GanttProjectModel();
        $projects = $projectModel->getUserProjectSummary($filter);
        foreach ($projects as &$p) {
            $p['progress'] = $p['status']==-1 ? -1 : ($p['status']==1 ? 100 : Fn::dateProgress($p['start'], $p['end'], '', 99));
        }
        $this->getView()->assign('title', '公开项目');
        $this->getView()->assign('projects', $projects);
    }

    public function viewAction() {
        $id = $this->getParam('id', 0);
        $project = (new GanttProjectModel())->get($id);
        $tasks = (new GanttTaskModel())->getAllTasks($id);
        $this->getView()->assign('project', $project);
        $this->getView()->assign('tasks', $tasks);
    }
}