<?php
/**
 * danny 2018-04-10
 * 获取zabbix系统的api
 */
class ZabbixApi extends DbClass
{
    private $json_rpc;
    private $user;
    private $pwd;
    private $version='';
    private $auth;
    private $error;
    private $sessionsTab='sessions';
    private $loginName ='';
    protected $table = '';
    protected $confName = 'zabbix';

    public function __construct($loginName='')
    {
    	$confName = !empty($this->confName) ? $this->confName : 'mysql';
		$conf = Yaf_Registry::get('config')->$confName;
		$this->json_rpc= $conf->rpcUrl;
		$this->user= $conf->user;
		$this->pwd= $conf->pwd;
		parent::__construct($conf->dsn, $conf->username, $conf->password);
		$this->loginName= $loginName;
		$this->iniAuth();

    }
    private function jsonRpcGet($data, $is_return = true)
    {
        $headers = array(
            'Content-Type: application/json-rpc',
        );
        
        // gf_dump($data);
        $res = gf_http_post($this->json_rpc, json_encode($data), $headers);
       	// gf_dump($res);
        // gf_ajax_success($res);
        if (!$is_return) {
            unset($res);
            return true;
        }
        $ret = json_decode($res, true);
        if (is_array($ret)) {
            if (array_key_exists('result', $ret)) {
                return $ret['result'];
            } else {
                $this->error = $ret['error']['message'];
                return false;
            }
        } else {
            // gf_shell_echo("request[$this->json_rpc], reponse[$res]");
            return false;
        }
    }

    private function makeRpcParams($method, $params)
    {
        return array(
            "jsonrpc" => "2.0",
            "method" => $method,
            "params" => $params,
            "auth" => $this->auth,
            "id" => 1,
        );
    }
    private function iniAuth(){
    	$auth= '';
    	if(!empty($this->auth)){
    		return ;
    	}
    	if(!empty($this->loginName)){
    		$userInfo=$this->getAll(
    			"select s.sessionid as sid,u.userid as uid from users_groups ug  
				join users u on u.userid = ug.userid
				left join sessions s on s.userid= u.userid 
				where u.alias = ? group by u.userid",
    			array($this->loginName)
    		);
    		if(empty($userInfo)){
    			// no user information
    			// gf_shell_echo('ini Auth failed!');
    			return ;
    		}
    		$userInfo= $userInfo[0];
    		$sessionid = md5(time()+rand());
    		if(empty($userInfo['sid'])){
    			//insert
    			$rs=$this->insert($this->sessionsTab,array(
    				'sessionid'=>$sessionid,
    				'userid'=>$userInfo['uid'],
    				'lastaccess'=>time(),
    				'status'=>0,
    			));	
    			if($rs>0){
    				$auth=$sessionid;
    			}else{

    			}
    		}else{
    			$auth = $userInfo['sid'];
    		}
    	}
    	$this->auth=$auth;
    }
    private function usePwd()
    {
        // get auth
        $data = array(
            "jsonrpc" => "2.0",
            "method" => 'user.login',
            "params" => array(
                'user' => $this->user,
                'password' => $this->pwd,
            ),
            "auth" => $this->auth,
            "id" => 1,
        );
        $this->auth = $this->jsonRpcGet($data);
    }
    public function getApiVersion(){
    	if(empty($this->version)){
	    	 $data = array(
	            "jsonrpc" => "2.0",
	            "method" => "apiinfo.version",
	            "params" => [],
	            "id" => 1
         	);
        	$this->version = $this->jsonRpcGet($data);
    	}
        return $this->version;
    
    }
    public function useToken($userName){
		$this->iniSession($userName);
		$this->auth =$token;
    }
    public function getHostItems($host, $names)
    {
        $params = array(
            "hostids" => $host,
            "filter" => array(
                "value_type" => array(0, 3),
            ),
            "output" => array(
                "name", "key_", "value_type", "delay",
            ),
            "sortfield" => "name",
        );
        $items = $this->jsonRpcGet($this->makeRpcParams('item.get', $params));
        unset($params);
        $ret = array();
        if (!$items) {
            return $ret;
        }
        foreach ($items as $item) {
            if (strpos($item['name'], '$1') !== false) {
                $name = str_replace('$1', preg_replace("/.*\[(.*)\].*/", "$1", $item['key_']), $item['name']);
            } else {
                $name = $item['name'];
            }
            if (in_array($name, $names)) {
                $ret[] = $item;
                unset($names[array_search($name, $names)]);
            }
            if (count($names) == 0) {
                break;
            }
        }
        unset($items);
        return $ret;
    }
    public function getItemHistory($item, $type,$startTime,$endTime)
    {
        $params = array(
            "history" => $type,
            "itemids" => $item,
            "output" => "extend",
            "sortfield" => "itemid",
            "sortorder" => "ASC",
            "time_from" => $startTime,
            "time_till" => $endTime,
            'limit'=>1000,
        );
        return $this->jsonRpcGet($this->makeRpcParams('history.get', $params), true);
    }
    public function getItemInfo($item)
    {
        $params = array(
            "itemids" => $item,
            "output" => array("name", "key_", "value_type", "hostid", "status", "state"),
            "selectHosts" => array("hostid", "name"),
            "webitems" => true,
        );
        return $this->jsonRpcGet($this->makeRpcParams('item.get', $params), true);
    }
    public function getHostGroup(){
    	$params=array(
    		'output'=>array('groupid','name')
    	);
    	return $this->jsonRpcGet($this->makeRpcParams('hostgroup.get', $params), true);
    	 
    }
    public function getScreen($params=array()){
    	return $this->jsonRpcGet($this->makeRpcParams('screen.get', $params), true);
    	 
    }
    public function getScreenItem($screenIds=array()){
    	$params=array(
    		'screenids' =>$screenIds,
    		// 'output'=>['resourceid','resourceid','screenid','screenitemid']
    	);
    	return $this->jsonRpcGet($this->makeRpcParams('screenitem.get', $params), true);
    	 
    }
    public function getGraphItem($itemids=array()){
    	$params=array(
    		'graphids' =>$itemids,
    		// 'output'=>['resourceid','resourceid','screenid','screenitemid']
    	);
    	return $this->jsonRpcGet($this->makeRpcParams('graphitem.get', $params), true);
    	 
    }
    public function getVersion()
    {
        return $this->version;
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public function getRpc()
    {
        return $this->json_rpc;
    }

    public function getError()
    {
        return $this->error;
    }
}
