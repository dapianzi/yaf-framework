<?php

/**
 *
 * @Author: Carl
 * @Since: 2018-04-04 14:22
 * Created by PhpStorm.
 */
class SwitchScriptController extends BaseController {

    public function indexAction() {
        $search = $this->getQuery('q', '');
        $this->assign('q', $search);
        if (!empty($search)) {
            $article = (new OtrsModel())->getArticleHtml($search);
            if ($article) {
                // 工单信息
                $article_info = $this->_extractArticleInfo($article);
                $this->assign('article_info', $article_info);
                // 输出脚本
                $switch_script = $this->_renderScript($article_info);
                $this->assign('switch_script', $switch_script);
            }
        }
    }


    public function _extractArticleInfo($article) {
//        gf_dump($article['text']);

        // todo 具体实现等客服模板
        preg_match_all('/^(.*?)[:：](.*)$/im', $article['text'], $matches);
        $res = [];
        foreach ($matches[1] as $k=>$m) {
            $res[trim($m)] = trim($matches[2][$k]);
        }
        // gf_dump($res);
        // return $res;
        return [
            'ip' => '127.0.0.1',
            '端口' => 'Ethernet0',
            '操作类型' => '关端口',
            'any' => '额外显示的信息',
            'http' => '//cloud.ws-cmdb.com:8089/',
            '垃圾信息' => '垃圾信息不会被显示',
        ];
    }


    public function _renderScript($article_info) {
        // 操作类型
        if (isset($article_info['操作类型'])) {
            $script_template = (new ScriptTemplateModel())->getTemplateByName($article_info['操作类型']);
//            switch ($script_template['content_type']) {
//
//            }
            return gf_render_template($script_template['content'], $article_info);
        }
    }


}