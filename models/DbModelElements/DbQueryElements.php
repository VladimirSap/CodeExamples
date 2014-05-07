<?php
// Класс содержт только статические методы
// Вспомогательные методы работы с классами
class DbQueryElements {
    public static function composeCreateSql() {

        $tableName = DbParameters::getConstant('tableName');
        $varSet = DbParameters::getVarSet(DbParameters::$dbType);

        $sql = "CREATE TABLE $tableName(";

        end($varSet);
        $endKey = key($varSet);

        foreach($varSet as $key=>$value) {
            $sql .= "$key $value";

            if ($key != $endKey) {
                $sql .= ", ";
            }
        }

        $sql .= ")";

        return $sql;
    }

    public static function prepareVarSet($arr, $shift) {
        if($shift) {
            array_shift($arr);
        }

        return implode(",", $arr);
    }

    public static function getQMarks() {
        $varSet = array_keys(
            DbParameters::getVarSet(DbParameters::$dbType));

        $idAutoInc = DbParameters::getConstant('idAutoInc');

        $amount = $idAutoInc ?
            sizeof($varSet)-1 :  sizeof($varSet);

        $marks = '?';
        for ($i=0; $i<$amount-1; $i++) {
            $marks .= ",?";
        }
        return $marks;
    }

    public static function sortByCategory($rows) {
        $goods = array();
        foreach($rows as $res) {
            $goods[$res['category']][]= $res;
        }

        return $goods;
    }
}