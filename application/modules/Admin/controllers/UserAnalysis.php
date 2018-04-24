<?php
/**
 * @Author: Carl
 * @since: 2018/4/23 11:13
 * Create by PhpStorm
 */
class UserAnalysisController extends BaseController {

    public function indexAction() {

    }

    public function generalDataAction() {
        $os = $this->getQuery('os', '');
        $channel = $this->getQuery('channel', '');
        $from = $this->getQuery('from', '');
        $to = $this->getQuery('to', '');

        $interval = 'daily';

        if (empty(strtotime($from))) {
            $from = gf_now(time()-30*86400);
        }
        if (empty(strtotime($to))) {
            $to = gf_now();
        }
//        $data = (new MongoDBModel())->getUserGeneral($os, $channel, $from, $to, $interval);
        $dateRange = $this->_dateRange($from, $to);
        gf_ajax_success([
            'range' => $dateRange,
            'series' => [
                'reg' => [
                    'name' => '注册',
                    'data' => $this->_randTestData(count($dateRange), 0, 100),
                ],
                'new' => [
                    'name' => '激活',
                    'data' => $this->_randTestData(count($dateRange), 0, 100),
                ],
                'act' => [
                    'name' => '活跃',
                    'data' => $this->_randTestData(count($dateRange), 0, 100),
                ],
                'pay' => [
                    'name' => '付费',
                    'data' => $this->_randTestData(count($dateRange), 0, 100),
                ],
                'regs' => [
                    'name' => '付费',
                    'data' => $this->_randTestData(count($dateRange), 100, 1000),
                ],
            ]
        ]);
    }

    public function _randTestData($count, $min, $max) {
        $ret = [];
        $i = 0;
        while ($i < $count) {
            $ret[] = rand($min, $max);
            $i++;
        }
        return $ret;
    }

    public function _dateRange($from, $to, $interval=86400) {
        $from = strtotime($from);
        $to = strtotime($to);
        $range = [];
        while ($from < $to) {
            $range[] = gf_now($from);
            $from+=$interval;
        }
        return $range;
    }

}