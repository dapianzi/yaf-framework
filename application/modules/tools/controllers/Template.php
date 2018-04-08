<?php

/**
 *
 * @Author: Carl
 * @Since: 2018-04-04 14:48
 * Created by PhpStorm.
 */
class TemplateController extends BaseController {

    public function indexAction() {
        $model = new ScriptTemplateModel();
        $sql = "SELECT s.*,u1.nickName creator,u2.nickName modifier FROM script_template s "
            ." LEFT JOIN user u1 ON s.create_by=u1.id "
            ." LEFT JOIN user u2 ON s.modify_by=u2.id WHERE s.status=0 ";
        $templates = $model->getAll($sql);
        $result = [];
        foreach ($templates as $t) {
            $t['format_content'] = $this->_format_template($t['content'], $t['content_type']);
            $result[$t['category']][] = $t;
        }
        $this->assign('result', $result);
        // category
        $this->assign('category', $model->getColEnum('category'));
        // content_type
        $this->assign('content_type', $model->getColEnum('content_type'));
    }

    public function saveAction() {
        $id = $this->getPost('id', 0);
        $name = $this->getPost('name', '');
        $category = $this->getPost('category', '');
        $content_type = $this->getPost('content_type', '');
        $content = $this->getPost('content', '');

        // valid unique name
        $model = new ScriptTemplateModel();
        $exist = $model->getColumn('SELECT id FROM script_template WHERE id<>? AND category=? AND name=?', array($id, $category, $name));
        if ($exist) {
            gf_ajax_error("模板<{$category} - {$name}>已存在");
        }

        if (empty($name)) {
            gf_ajax_error("模板名称不能为空已存在");
        }
        if (empty($category)) {
            gf_ajax_error("模板类别错误");
        }
        if (empty($content_type)) {
            gf_ajax_error("模板内容类型错误");
        }

        $data = [
            'name' => $name,
            'category' => $category,
            'content' => $content,
            'content_type' => $content_type,
            'modify_by' => $this->user['id'],
            'modify_time' => date('Y-m-d H:i:s'),
        ];
        if ($id > 0) {
            // modify
            $info = $model->get($id);
            foreach ($data as $k=>$v) {
                if ($v==$info[$k]) {
                    unset($data[$k]);
                }
            }
            if ($data) {
                $model->mod($id, $data);
            }
            gf_ajax_success($model->getLastSQL());
        } else {
            // create new
            $data['create_by'] = $data['modify_by'];
            $data['create_time'] = $data['modify_time'];
            $model->add($data);
            gf_ajax_success($model->getLastInsertId());
        }

    }

    public function getAction() {
        $id = $this->getParam('id', 0);

        $res = (new ScriptTemplateModel())->get($id);
        $res ? gf_ajax_success($res) : gf_ajax_error('No templates found!');
    }

    public function delAction() {
        $id = $this->getPost('id', 0);
        if ($id > 0) {
            //(new ScriptTemplateModel())->del($id);
            (new ScriptTemplateModel())->set($id, 'status', -1);
            gf_ajax_success($id);
        } else {
            gf_ajax_error('Invalid Params');
        }
    }


    public function _format_template($s, $type) {
        switch ($type) {
            case 'html':
                $s = html_entity_decode($s);break;
            default:
                $s = htmlentities($s);
        }
        return $s;
    }

}