<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22 0022
 * Time: 10:55
 */

class WebController extends BaseController
{
    public $auth = FALSE;

    public function init() {
        // inti config
        $conf = Yaf_Registry::get('config');
        $this->conf = $conf;
        // init request mode
        $this->is_ajax = $this->getRequest()->isXmlHttpRequest ();

        $site = [
            'title' => '魔境大冒险DEMO | 首页',
            'name' => '魔境大冒险',
            'keywords' => '魔境,大冒险,捕鱼,达人',
            'description' => '魔境大冒险，师妹玩过的船新版本！',
            'copyright' => sprintf('Copyright &copy; %s 标配一个亿项目, Inc. ', date('Y')),
            'ISBN' => 'ISBN：xxx-x-xxxxxx-xx-x'
        ];
        $this->assign('site', $site);
    }
}