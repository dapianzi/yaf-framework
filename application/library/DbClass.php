<?php
/**
 * Yaf PDO class.
 * @Author: Carl
 * @Since: 2017/4/7 15:42
 * Created by PhpStorm.
 */
class DbClass
{
    private $dbLink;

    private $lastSql;
    private $lastInsertId;
    private $errMessage;


    public function __construct($dsn, $username, $password) {
        $opts = array (
            PDO::ATTR_ERRMODE  => PDO::ERRMODE_EXCEPTION,
            // Cancel one specific SQL mode option that RackTables has been non-compliant
            // with but which used to be off by default until MySQL 5.7. As soon as
            // respective SQL queries and table columns become compliant with those options
            // stop changing @@SQL_MODE but still keep SET NAMES in place.
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8", @@SQL_MODE = REPLACE(@@SQL_MODE, "NO_ZERO_DATE", "")',
        );
        if (isset ($pdo_bufsize))
            $opts[PDO::MYSQL_ATTR_MAX_BUFFER_SIZE] = $pdo_bufsize;
        if (isset ($pdo_ssl_key))
            $opts[PDO::MYSQL_ATTR_SSL_KEY] = $pdo_ssl_key;
        if (isset ($pdo_ssl_cert))
            $opts[PDO::MYSQL_ATTR_SSL_CERT] = $pdo_ssl_cert;
        if (isset ($pdo_ssl_ca))
            $opts[PDO::MYSQL_ATTR_SSL_CA] = $pdo_ssl_ca;
        try
        {
            $this->dbLink = new PDO ($dsn, $username, $password, $opts);
        }
        catch (Exception $e)
        {
            $this->errMessage = "Database connect failed:\n\n" . $e->getMessage();
            throw new WSException($this->errMessage);
        }
    }

    public function isConnectOk() {
        return !!$this->dbLink;
    }

    public function execute($sql, $param = array()) {
        try {
            $pre = $this->dbLink->prepare($sql);
            $pre->execute($param);
            $this->lastSql = $pre->queryString;
            return $pre;
        } catch (PDOException $e) {
            $this->errMessage = $e->getMessage();
            throw new WSException($e);
        }
    }

    public function insert($table, $columns) {
        $sql = " INSERT INTO {$table} (`" . implode ('`, `', array_keys ($columns));
        $sql .= '`) VALUES (' . $this->questionMarks (count ($columns)) . ')';
        // Now the query should be as follows:
        // INSERT INTO table (c1, c2, c3) VALUES (?, ?, ?)
        $res = $this->execute($sql, array_values($columns))->rowCount();
        if ($res > 0) {
            return $this->dbLink->lastInsertId();
        } else {
            return FALSE;
        }
    }

    public function update($table, $param, $where, $conjunction = 'AND') {
        if (!count($param)) {
            $this->errMessage = 'update must have set.';
            throw new WSException('update must have set.');
        }
        if (!count($where)) {
            $this->errMessage = 'update must have where.';
            throw new WSException('update must have where.');
        }
        $whereValues = array();
        $sql = " UPDATE $table SET " . $this->makeSetSQL($param) . ' WHERE ' . $this->makeWhereSQL($where, $conjunction, $whereValues);
        return $this->execute($sql, array_merge (array_values ($param), $whereValues))->rowCount();
    }

    public function delete($table, $where, $conjunction = 'AND') {
        if (!count($where)) {
            $this->errMessage = 'delete must have where.';
            throw new WSException('delete must have where.');
        }
        $whereValues = array();
        $sql = " DELETE FROM $table WHERE " . $this->makeWhereSQL($where, $conjunction, $whereValues);
        print_r($sql);die;
        return $this->execute ($sql, $whereValues)->rowCount();
    }

    /**
     * 开启事务
     * @return bool
     */
    public function begin() {
        return $this->dbLink->beginTransaction();
    }

    /**
     * 事务提交
     * @return bool
     */
    public function commit() {
        return $this->dbLink->commit();
    }

    /**
     * 事务回滚
     * @return bool
     */
    public function rollBack() {
        return $this->dbLink->rollBack();
    }

    public function getColumn($sql, $param = array(), $col = 0) {
        return $this->execute($sql, $param)->fetchColumn($col);
    }

    public function getKeyValue($sql, $param = array()) {
        return $this->execute($sql, $param)->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function getCount($sql, $param = array()) {
        return $this->execute($sql, $param)->rowCount();
    }

    public function getAll($sql, $param = array()) {
        return $this->execute($sql, $param)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRow($sql, $param = array()) {
        return $this->execute($sql, $param)->fetch(PDO::FETCH_ASSOC);
    }

    public function makeSetSQL($columns) {
        if (! count ($columns)) {
            throw new WSException ('columns must not be empty');
        }
        $tmp = array();
        // Same syntax works for NULL as well.
        foreach ($columns as $col => $val) {
            $tmp[] = "`${col}`=?";
        }
        return implode (', ', $tmp);
    }

    public function makeWhereSQL ($where_columns, $conjunction, &$params = array()) {
        if (! in_array (strtoupper ($conjunction), array ('AND', '&&', 'OR', '||', 'XOR'))) {
            throw new WSException ('conjunction'. $conjunction. 'invalid operator');
        }
        if (! count ($where_columns)) {
            throw new WSException ('where_columns must not be empty');
        }
        $params = array();
        $tmp = array();
        foreach ($where_columns as $colName => $colValue)
            if ($colValue === NULL)
                $tmp[] = "$colName IS NULL";
            elseif (is_array ($colValue))
            {
                // Suppress any string keys to keep array_merge() from overwriting.
                $params = array_merge ($params, array_values ($colValue));
                $tmp[] = sprintf ('%s IN(%s)', $colName, $this->questionMarks (count ($colValue)));
            }
            else
            {
                $tmp[] = "${colName}=?";
                $params[] = $colValue;
            }
        return implode (" ${conjunction} ", $tmp);
    }

    public function questionMarks($count) {
        if ($count <= 0) {
            throw new WSException('count must be greater than zero');
        }
        return implode(', ', array_fill(0, $count, '?'));
    }

    public function getLastSQL() {
        return $this->lastSql;
    }

    public function getLastInsertId() {
        return $this->dbLink->lastInsertId();
    }

    public function getError() {
        return $this->errMessage;
    }
}
