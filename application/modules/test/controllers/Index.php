<?php
/**
 * @name IndexController
 * @author Carl
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {

	public function indexAction() {
        $this->forward('dist');
        return FALSE;
	}

    public function distAction() {

    }

}
