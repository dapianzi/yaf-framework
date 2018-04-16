<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/4/6 16:59
 * Created by PhpStorm.
 */
class IndexController extends CommonapiController {

    public function indexAction() {
        $params = $this->getParams();

        gf_ajax_success($params);
    }
}