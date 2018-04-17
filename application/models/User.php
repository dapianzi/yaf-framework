<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:54
 */

class UserModel extends PDOModel {

    /**
     * 获取用户信息
     * @param int $uid 用户信息
     * @return array
     */
    public function getUserInfo($uid) {
        $sql = 'SELECT u.id,u.username,u.nickname,u.status,u.salt,u.password,u.email,r.id role_id,r.status role_status FROM user u '.
            ' LEFT JOIN roles r ON u.role_id=r.id WHERE u.id=? ';
        return $this->getRow($sql, array($uid));
    }

    /**
     * 按用户名获取用户信息
     * @param $username
     * @return mixed
     */
    public function getUserByName($username) {
        $sql = 'SELECT u.id,u.username,u.status,u.role_id,u.password,u.salt FROM user u WHERE u.username=? ';
        return $this->getRow($sql, array($username));
    }

    /**
     * 写入用户日志
     * @param $data
     * @return bool|string
     */
    public function addUserLogs($data) {
        return $this->insert('logs', $data);
    }

    /**
     * 获取用户日志
     * @param $from
     * @param $to
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getUserLogs($from, $to, $offset=0, $limit=50){
        $sql = 'SELECT l.*,u.username FROM logs l LEFT JOIN user u ON l.uid=u.id '
            .' WHERE u.status=1 AND u.adate BETWEEN ? AND ? ORDER BY adate DESC limit '.$offset.','.$limit;
        $data = $this->getAll($sql, array($from, $to));
        $count = $this->getCount('SELECT id FROM logs WHERE status=1 AND adate BETWEEN ? AND ?', array($from, $to));
        return array($data, $count);
    }

    /**
     * 设置过期的用户操作日志
     * @param string $time
     * @return bool|PDOStatement
     * @throws SysException
     */
    public function setUserLogsExpire($time = ''){
        if (empty($time)) {
            $time = date('Y-m-d H:i:s', time()-365*86400);
        }
        if(preg_match("/^\d{4}(-\d{2}){2} (\d{2}:){2}\d{2}$/i")){
            return $this->execute("UPDATE {$this->table} SET status=-1 WHERE adate < ? ", array($time) );
        } else {
            throw new SysException('Datetime format incorrect!');
        }
    }


    public function login($user) {
//        $this->insert('session', array(
//            'uid' => $user['id'],
//            'token' => $user['id'],
//        ));
    }

}