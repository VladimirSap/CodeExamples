<?php
// Класс контролирует, какой тип базы используется
// и с какой таблицей осуществляется работа
class DbParameters {
    public static $dbParams;

    public static $dbType = DbParameters::SQLITE;

    CONST SQLITE = 'sqlite:';

    CONST MYSQL_HEAD = 'mysql:host=localhost';
    CONST MYSQL_DB_NAME = 'compShop';
    CONST MYSQL_USER = 'root';
    CONST MYSQL_PW = '';

    // Список таблиц, имеющихся в проекте
    // Перебором списка эти таблицы создаются в БД
    public static $tablesInDb = array(DbGoodsParams::dbClassName,
                                        DbOrdersParams::dbClassName);

    public static function setDbType($dbName) {
        if ($dbName == "SQLite") {
            self::$dbType = self::SQLITE;
        } else if ($dbName == "MySQL") {
            self::$dbType = self::MYSQL_HEAD;
        } else {
            throw new Exception("Error type of db" . $dbName);
        }
    }

    public static function setDbParams($name) {
        // Динамически изменить параметры для работы с БД
        self::$dbParams = $name;
    }

    public static function getVarSet($dbType) {
        if ($dbType == self::MYSQL_HEAD) {
            $varSet = self::getVariable('varSetMySQL');
        }
        else if ($dbType == self::SQLITE) {
            $varSet = self::getVariable('varSetSQLite');
        }
        else {
            throw new Exception("Error type of db" . $dbType);
        }

        return $varSet;
    }

    // Методы доступа к параметрам таблиц (рефлексия)
    public static function getVariable($var) {
        $class = new ReflectionClass(self::$dbParams);
        return $class->getStaticPropertyValue($var);
    }

    public static function getConstant($var) {
        $class = new ReflectionClass(self::$dbParams);
        return $class->getConstant($var);
    }

    public static function setVariable($var, $value) {
        $class = new ReflectionClass(self::$dbParams);
        $class->setStaticPropertyValue($var, $value);
    }
}