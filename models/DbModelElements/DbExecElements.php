<?php

class DbExecElements {
    public static function getSQLitePdo() {
        return new PDO(DbParameters::$dbType. DbParameters::getConstant('dbFileName'));
    }

    public static function getMySqlPdo($toCreate) {
        if ($toCreate) {
            $db = new PDO(
                DbParameters::MYSQL_HEAD,
                DbParameters::MYSQL_USER,
                DbParameters::MYSQL_PW);
        } else {
            $db = new PDO(DbParameters::MYSQL_HEAD .";dbname=".
                DbParameters::MYSQL_DB_NAME,
                DbParameters::MYSQL_USER,
                DbParameters::MYSQL_PW);
        }
        return $db;
    }

    public static function executeQuery($sql) {
        $db = DbParameters::getVariable('db');
        $result = $db->query($sql);

        if ($result === false) {
            throw new Exception("Error while deleting all values!");
        } else {
            return $result;
        }
    }

    public static function prepareAndExecute($sql, $arr) {
        $db = DbParameters::getVariable('db');

        $stmt = $db->prepare($sql);
        $result = $stmt->execute($arr);

        if ($result === false) {
            throw new Exception("Error operation execution");
        }
    }
} 