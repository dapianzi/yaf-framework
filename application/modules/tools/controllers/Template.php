<?php

/**
 *
 * @Author: Carl
 * @Since: 2018-04-04 14:48
 * Created by PhpStorm.
 */
class TemplateController extends BaseController {

    public function indexAction() {
        $sql = "SELECT s.*,u1.nickName creator,u2.nickName modifier FROM script_template s LEFT JOIN user u1 ON s.create_by= WHERE status=0";
        $templates = (new ScriptTemplateModel())->getAll("SELECT * FROM script_template WHERE status=0");
        $categorys = [];
        foreach ($templates as $t) {
            $t['info'] = 'haha';
            $categorys[$t['category']][] = $t;
        }
        $this->assign('categorys', $categorys);
    }

    public function addTemplateAction() {

    }

    public function modTemplateAction() {

    }

    public function delTemplateAction() {
        $id = $this->getPost('id', 0);
        if ($id > 0) {
            (new ScriptTemplateModel())->del($id);
            gf_ajax_success('ok');
        } else {
            gf_ajax_error('Invalid Params');
        }

    }

}