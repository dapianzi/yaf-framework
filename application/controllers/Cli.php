<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/4/6 17:14
 * Created by PhpStorm.
 */
class CliController extends BaseController
{
    function indexAction() {
        echo 'Running ok in Cli..';
    }

    function runMysqlAction() {
        for ( ; ; ) {
            $tbs = $this->dbLink->getCount(" SHOW TABLES ");
            echo date('[Y-m-d H:i:s] ') . ' DB tables count: '.$tbs."\n";
            sleep(3);
        }
    }


    function lineNoCollectionAction() {
        $dbZabbix = new DbModel('zabbix');
        $dbLocal = new DbModel('mysql');

        $sql = " SELECT itemid,a.name item_name,key_,b.hostid,b.host,b.name host_name FROM zabbix.items a LEFT JOIN hosts b ON a.hostid=b.hostid WHERE itemid>? ";
        $items = $dbZabbix->getAll($sql, array(76896));
        #$this->shellEcho(count($items));exit;
        foreach ($items as $i) {
            $data = array(
                'item_id' => $i['itemid'],
                'item_name' => $i['item_name'],
                'item_key' => $i['key_'],
                'host_id' => $i['hostid'],
                'host' => $i['host'],
                'host_name' => $i['host_name'],
                'item_value' => $dbZabbix->getColumn('SELECT `value` FROM `zabbix`.`history_text` WHERE itemid=? ORDER BY id DESC LIMIT 1', 0, array($i['itemid']))
            );
            $dbLocal->insert('line_no_collection', $data);
        }


        return FALSE;
    }

    function shellColor($str, $color='') {
        switch(strtolower($color)) {
            case 'red': {

                break;
            }
            case 'green': {

                break;
            }
            case 'blue': {

                break;
            }
            default:
                return $str;
        }
    }

    function shellEcho($str) {
        echo date('[Y-m-d H:i:s] ') . $str . "\n";
    }

    function findServerAction() {
        $dbLocal = new DbModel('mysql');
        $sql = "SELECT item_value FROM rt_db.line_no_collection where item_value<>''";
        $res = $dbLocal->getAll($sql);
        foreach ($res as $r) {
            $value = explode('-', $r['item_value']);
            if (count($value) == 12) {
                $this->shellEcho(' ');
                #$this->shellEcho('  [Location] '. $value[0].'-'.$value[1]);
                #$this->shellEcho('  [Rack] '. $value[2].'-'.$value[3]);
                #$this->shellEcho('  [Unit] '. $value[4]);
                #$this->shellEcho('  [Port] '. $value[7].'/'.$value[8].'/'.$value[9]);

                $sql = " SELECT id FROM rack WHERE location_name=? AND name=? ";
                $rack = $dbLocal->getColumn($sql, 0, array('WS-'.$value[0].'-'.$value[1], $value[2].'-'.$value[3]));
                //$this->shellEcho($rack);
                $sql = " SELECT id FROM port WHERE object_id=(SELECT object_id FROM rackspace WHERE rack_id=? AND unit_no=? LIMIT 1) AND name=? ";

                $port_id = $dbLocal->getColumn($sql, 0, array($rack, trim(strtoupper($value[4]), 'U'), $value[7].'/'.$value[8].'/'.$value[9]));
                if ($port_id > 0) {

                    $dbLocal->update('port', array('label'=>$r['item_value']), array('id'=>$port_id));
                }

                $this->shellEcho('<==========================================================>');

            }
        }

        return FALSE;
    }




}