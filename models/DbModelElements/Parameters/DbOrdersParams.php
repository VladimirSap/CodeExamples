<?php
class DbOrdersParams {

    public static $db = null;

    CONST dbClassName = 'DbOrdersParams';
    CONST tableName = 'orders';
    CONST dbFileName = 'Db/orders.db';
    CONST idAutoInc = true;

    public static $varSetSQLite =
        array('id' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
            'userName' => 'TEXT',
            'orderData' => 'TEXT',
            'datetime' => 'INTEGER',
            'email' => 'TEXT');


        public static $varSetMySQL =
            array('id' => 'INT AUTO_INCREMENT PRIMARY KEY',
                'userName' => 'VARCHAR(30)',
                'orderData' => 'VARCHAR(30)',
                'datetime' => 'INT',
                'email' => 'VARCHAR(30)');
}
