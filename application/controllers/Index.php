<?php
/**
 * Created by PhpStorm.
 * User: Carl
 * Date: 2018/4/22 0022
 * Time: 10:55
 */

class IndexController extends WebController
{

    public function indexAction() {

    }

    public function orderAction() {
        $items = [
            [
                'name' => '首冲',
                'price' => 6.00,
                'details' => '首冲特惠大礼包',
                'items' => [
                    ['item_id' => 1, 'name' => '钻石', 'type' => 'coins', 'amount' => 100],
                    ['item_id' => 2, 'name' => '金币', 'type' => 'coins', 'amount' => 10000],
                    ['item_id' => 3, 'name' => '普通强化石', 'type' => 'items', 'amount' => 5],
                    ['item_id' => 10, 'name' => '锁定', 'type' => 'skills', 'amount' => 3],
                ]
            ],
            [
                'name' => '月卡贵族',
                'price' => 28.00,
                'details' => '贵族大礼包',
                'items' => [
                    ['item_id' => 1, 'name' => '钻石', 'type' => 'coins', 'amount' => 20],
                    ['item_id' => 2, 'name' => '金币', 'type' => 'coins', 'amount' => 20000],
                    ['item_id' => 10, 'name' => '锁定', 'type' => 'skills', 'amount' => 5],
                ]
            ],
            [
                'name' => 'VIP1',
                'price' => 20.00,
                'details' => 'VIP1专属礼包',
                'items' => [
                    ['item_id' => 1, 'name' => '钻石', 'type' => 'coins', 'amount' => 100],
                    ['item_id' => 2, 'name' => '金币', 'type' => 'coins', 'amount' => 10000],
                    ['item_id' => 3, 'name' => '普通强化石', 'type' => 'items', 'amount' => 10],
                    ['item_id' => 10, 'name' => '专注', 'type' => 'skills', 'amount' => 3],
                ]
            ],
            [
                'name' => 'VIP2',
                'price' => 58.00,
                'details' => 'VIP2专属礼包',
                'items' => [
                    ['item_id' => 1, 'name' => '钻石', 'type' => 'coins', 'amount' => 200],
                    ['item_id' => 2, 'name' => '金币', 'type' => 'coins', 'amount' => 50000],
                    ['item_id' => 3, 'name' => '高级强化石', 'type' => 'items', 'amount' => 5],
                    ['item_id' => 10, 'name' => '专注', 'type' => 'skills', 'amount' => 5],
                ]
            ],
            [
                'name' => 'VIP3',
                'price' => 168.00,
                'details' => 'VIP2专属礼包',
                'items' => [
                    ['item_id' => 1, 'name' => '钻石', 'type' => 'coins', 'amount' => 300],
                    ['item_id' => 2, 'name' => '金币', 'type' => 'coins', 'amount' => 100000],
                    ['item_id' => 3, 'name' => '高级强化石', 'type' => 'items', 'amount' => 10],
                    ['item_id' => 10, 'name' => '专注', 'type' => 'skills', 'amount' => 5],
                ]
            ],
            [
                'name' => 'VIP4',
                'price' => 428.00,
                'details' => 'VIP2专属礼包',
                'items' => [
                    ['item_id' => 1, 'name' => '钻石', 'type' => 'coins', 'amount' => 400],
                    ['item_id' => 2, 'name' => '金币', 'type' => 'coins', 'amount' => 500000],
                    ['item_id' => 3, 'name' => '黄金强化石', 'type' => 'items', 'amount' => 5],
                    ['item_id' => 10, 'name' => '专注', 'type' => 'skills', 'amount' => 8],
                ]
            ],
            [
                'name' => 'VIP5',
                'price' => 1288.00,
                'details' => 'VIP2专属礼包',
                'items' => [
                    ['item_id' => 1, 'name' => '钻石', 'type' => 'coins', 'amount' => 500],
                    ['item_id' => 2, 'name' => '金币', 'type' => 'coins', 'amount' => 1000000],
                    ['item_id' => 3, 'name' => '黄金强化石', 'type' => 'items', 'amount' => 10],
                    ['item_id' => 10, 'name' => '专注', 'type' => 'skills', 'amount' => 10],
                ]
            ],
            [
                'name' => 'VIP6',
                'price' => 3888.00,
                'details' => 'VIP2专属礼包',
                'items' => [
                    ['item_id' => 1, 'name' => '钻石', 'type' => 'coins', 'amount' => 600],
                    ['item_id' => 2, 'name' => '金币', 'type' => 'coins', 'amount' => 5000000],
                    ['item_id' => 3, 'name' => '铂金强化石', 'type' => 'items', 'amount' => 5],
                    ['item_id' => 10, 'name' => '专注', 'type' => 'skills', 'amount' => 10],
                ]
            ],
        ];
        $this->assign('items', $items);
    }
}