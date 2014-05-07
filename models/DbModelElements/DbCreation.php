<?php
// Только статические методы
// Функции создания или соединения с БД и их таблицами
class DbCreation {
    // См db диаграмму, описывающую деятельность данного класса

    public static function connectDb() {
        if (DbParameters::$dbType == DbParameters::MYSQL_HEAD) {
            self::connectMySQL();
        } else if (DbParameters::$dbType == DbParameters::SQLITE){
            self::constructSQLite();
        } else {
            throw new Exception("Error type of db" . DbParameters::$dbType);
        }
    }

    private static function constructSQLite() {
        foreach(DbParameters::$tablesInDb as $tables) {
            DbParameters::setDbParams($tables);
            self::connectSQLite();
        }
    }

    private static function connectSQLite() {
        if (is_file(DbParameters::getConstant('dbFileName'))) {
            // Если БД уже существует
            DbParameters::setVariable(
                'db', DbExecElements::getSQLitePdo());
        }
        else {
            DbParameters::setVariable(
                'db', DbExecElements::getSQLitePdo());
            self::executeCreateDb();
        }
    }

    private static function connectMySQL() {
        if (!self::createMySqlDb()) {
            // Если база данных уже существует
            self::constructMySQLTables(false);
        } else {
            // Если не существовала, то была создана
            // при вызове метода в первом if
            self::constructMySQLTables(true);
        }
    }

    private static function constructMySQLTables($toCreate) {
        foreach(DbParameters::$tablesInDb as $tables) {
            DbParameters::setDbParams($tables);
            DbParameters::setVariable(
                'db', DbExecElements::getMySqlPdo(false));
            if ($toCreate) { self::executeCreateDb(); }
        }
    }

    private static function createMySqlDb() {
        $db = DbExecElements::getMySqlPdo(true);
        $dbName = DbParameters::MYSQL_DB_NAME;
        $createQuery = $db->prepare("CREATE DATABASE $dbName");

        return $createQuery->execute();
    }

    private static function executeCreateDb() {
        $sql = DbQueryElements::composeCreateSql();
        $db = DbParameters::getVariable('db');
        $tableName = DbParameters::getConstant('tableName');

        $result = $db->exec($sql);

        if ($result === false) {
            throw new Exception("Can't create database:" . $tableName);
        }
    }
} 