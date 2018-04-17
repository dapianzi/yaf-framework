<?php
/**
 * User: danny
 * Date: 2018/4/8
 * Time: 11:13
 */
class ChartsController extends BaseController {
    private $zabbixApiObj;
    function init(){
        parent::init();
        // print_r($this->user);exit;
        // $this->zabbixApiObj = new ZabbixAPI($this->user['name']);
        $this->zabbixApiObj = new ZabbixAPI($this->user['name']);
    }
    private function replaceName($name,$key){
        preg_match_all('/\[(.*)\]$/',$key, $matches,PREG_PATTERN_ORDER); 
        if(isset($matches[1][0])){
            return str_replace("$1", $matches[1][0], $name);
        }
        return $name;
    }
    function indexAction() {
        $screens= $this->getScreenList();
        $this->assign('screens',$screens);
    }
    function getScreenList(){
        $apiObj =$this->zabbixApiObj;
        $params=array();
        $screenList=$apiObj->getScreen();
        return  $screenList; 
    }
    function getTestChartsData(){
         /*start-*/
        $data1=array();
        $data2=array();
        $i=10;
        $s=time();
        while($i>0){
            // echo rand(0,10);exit;
            // $t= $s.$i.rand(0,10).rand(0,10).rand(0,10).rand(0,10);
            // $t ='-'.$i.' days';
            $t= ($s-24*3600*(11-$i)).'000';
            $v=array(
                    'name'=>$t,
                    'value'=>array($t,rand(0,100))
                );
            $data1[]=$v;
            $v['value'][1]=rand(50,150);
            $data2[]=$v;
            $i--;
        }
        $data[]=array(
            'series'=>$data1,
            'title'=>'data1',
        );
        $data[]=array(
            'series'=>$data2,
            'title'=>'data2',
        );
        gf_ajax_success($data);
        return false;
    }
    function getScreenItemAction(){
        // sleep(6);
        // $this->getTestChartsData();
        $itemId=$this->getQuery('id',NULL);
        $timeFrom =$this->getQuery('time_from',date("-1 days"));
        $timeTill =$this->getQuery('time_till',time());
        if(NULL===$itemId){
            gf_dump('itemid not found');
            exit;
        }
        $apiObj =$this->zabbixApiObj;
        $params=array();
        $rs=$apiObj->getScreenItem($itemId);
        if(empty($rs)){
            gf_dump('empty rs');
            exit;
        }
        /*end-*/

        $rs =$rs[0];
        $resourceid =$rs['resourceid'];
        $rs= $apiObj->getGraphItem($resourceid);
        $itemIds =array();
        foreach ($rs as $key => $value) {
            $itemIds[]=$value['itemid'];
        }
        $rsInfo=$apiObj->getItemInfo($itemIds);
        // gf_dump($rsInfo);
        if(empty($rsInfo)){
            gf_ajax_error('itemid not found');
        }
        
        foreach ($rsInfo as $key => $value) {
            $chartName =$value['name'];
            $chartName = $this->replaceName($chartName,$value['key_']);
            $rs = $apiObj->getItemHistory(array($value['itemid']),$value['value_type'],$timeFrom,$timeTill);
            if(empty($rs)){
                gf_ajax_error('item history not found');
            }
            $valueList =array();
            foreach ($rs as $key => $value) {
                $valueList[]=array(
                    'name'=>$value['clock'].mb_substr($value['ns'],0,3),
                    'value'=>array($value['clock'].mb_substr($value['ns'],0,3),$value['value'])
                );
            }
            $data[]=array(
            'series'=>$valueList,
            'title'=>$chartName,
            );
        }
        // gf_dump($data);
        gf_ajax_success($data);
        return false;
    }

    function ghgAction(){
        $rsInfo=$this->zabbixApiObj->getHostGroup();
        gf_dump($rsInfo);
        return false;
    }
    function getDataAction(){
        $itemId = 27227;
        $itemId=$this->getQuery('id',NULL);
        $timeFrom =$this->getQuery('time_from',date("-1 days"));
        $timeTill =$this->getQuery('time_till',time());
        if(NULL===$itemId){
            $itemId = 27221;
        }
        $rsInfo=$this->zabbixApiObj->getItemInfo($itemId);
        // gf_dump($rsInfo);
        if(empty($rsInfo)){
            gf_ajax_error('itemid not found');
        }
        $rsInfo=$rsInfo[0];
        
        $chartName =$rsInfo['name'];
        $chartName = $this->replaceName($chartName,$rsInfo['key_']);
        $rs = $this->zabbixApiObj->getItemHistory(array($itemId),$rsInfo['value_type'],$timeFrom,$timeTill);
        if(empty($rs)){
            gf_ajax_error('item history not found');
        }
        $valueList =array();
        foreach ($rs as $key => $value) {
            $valueList[]=array(
                'name'=>$value['clock'].mb_substr($value['ns'],0,3),
                'value'=>array($value['clock'].mb_substr($value['ns'],0,3),$value['value'])
            );
        }
        gf_ajax_success(array(
            'series'=>$valueList,
            'title'=>$chartName,
            )
        );
        return false;
    }
	function testTools() {

		$db = new PDOClass('mysql:host=183.2.213.63;dbname=zabbix;port=3306', 'kf_zabbix', 'kfzabbixpass');
		$rs = $db->getAll("SELECT
 table_name,column_name
FROM
    information_schema. COLUMNS
WHERE
    TABLE_SCHEMA = 'zabbix' and column_name ='itemid';");
		foreach ($rs as $key => $value) {
			// gf_dump($value);
			$column = $value['column_name'];
			$table = $value['table_name'];
			$find = "danny";
			$rs = $db->getAll("select * from {$table} where {$column} =27112 limit 5 ");
			if (!empty($rs)) {
                echo $table;
				gf_dump($rs);
			}
			// exit;
			usleep(100);
		}

		// gf_dump(array_values($rs));
	}
}