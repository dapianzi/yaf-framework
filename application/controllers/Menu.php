<?php
/**
 * Created by PhpStorm.
 * User: sky / ouyangdongming
 * Date: 2018/4/3
 * Time: 17:38
 */
class MenuController extends BaseController {

    function indexAction(){

    }

    function listAction(){
        $page= $this->getQuery('page');
        $limit= $this->getQuery('limit');
        $key= $this->getQuery('key');
        $MenuModel=new MenuModel();
        $meun=$MenuModel->getMenu($key);
        echo json_encode($meun,JSON_UNESCAPED_UNICODE);
        return false;
    }
}