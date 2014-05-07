<?php
class DbGoodsParams {

    public static $db = null;

    CONST dbClassName = 'DbGoodsParams';
    CONST tableName = 'goods';
    CONST dbFileName = 'Db/goods.db';
    CONST idAutoInc = true;

    public static $varSetSQLite =
        array('id'=>'INTEGER PRIMARY KEY AUTOINCREMENT',
            'title'=>'TEXT',
            'category'=>'TEXT',
            'description'=>'TEXT',
            'price'=>'INTEGER',
            'datetime'=> 'INTEGER');

    public static $varSetMySQL =
         array('id'=>'INT AUTO_INCREMENT PRIMARY KEY',
             'title'=>'VARCHAR(30)',
             'category'=>'VARCHAR(30)',
             'description'=>'VARCHAR(30)',
             'price'=>'INT',
             'datetime'=> 'INT');
}