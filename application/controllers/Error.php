<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author Carl
 */

class ErrorController extends Yaf_Controller_Abstract {

	//从2.1开始, errorAction支持直接通过参数获取异常
//	public function errorAction($exception) {
//		//1. assign to view engine
//		$this->getView()->assign("exception", $exception);
//		//5. render by Yaf
//	}

	public function errorAction($exception) {
        //functionClass::dump(Yaf_Application::$modules);
        switch($exception->getCode()) {

            case YAF_ERR_NOTFOUND_MODULE:
            case YAF_ERR_NOTFOUND_CONTROLLER:
            case YAF_ERR_NOTFOUND_ACTION: {
                //404
                header("HTTP/1.1 404 Not Found");
                break;
            }
            case YAF_ERR_AUTOLOAD_FAILED: {
                header("HTTP/1.1 503 Server Error.");
            }
            default:
            case SYS_EXCEPTION: {
//				header("HTTP/1.1 404 Not Found");
                if ($this->getRequest()->isXmlHttpRequest()) {
                    gf_ajax_error($exception->getMessage());
                }
                break;
            }
        }
        if (Yaf_Application::app()->environ() !== 'product') {

            $this->getView()->assign('exception', $exception);
            $this->getView()->display('public/error.html');
            return FALSE;
        } else {
            $this->getView()->assign('error', '');
            $this->getView()->display('public/404.html');
            return FALSE;
        }
	}
}
