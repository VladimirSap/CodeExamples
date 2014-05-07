<?php

require_once 'autoload.php';
spl_autoload_register('__autoload');

class DbModelTest extends PHPUnit_Framework_TestCase {

    private static $db = null;

    private static $ids = array();
    private static $names = array();

    public static function setUpBeforeClass() {
        global $argv;

        DbParameters::setDbType($argv[2]);
        self::$db = DBModel::getInstance();
    }

    protected function setUp() {

        DbParameters::setDbParams('DbGoodsParams');

        $good1 = new Goods('ПК1', 1, "Супер ПК1", 500);
        $good2 = new Goods('Ноут1', 2, "Супер ноут1", 1500);
        $good3 = new Goods('Смартфон1', 3, "Супер смарфон1", 200);

        self::$db->insertData($good1->getAsArray());
        self::$db->insertData($good2->getAsArray());
        self::$db->insertData($good3->getAsArray());

        $rows = self::$db->getAllData();

        self::$ids[] = $rows[0]['id'];
        self::$ids[] = $rows[1]['id'];

        self::$names[] = $rows[0]['title'];
        self::$names[] = $rows[1]['title'];
    }

    protected function tearDown() {
        self::$db->deleteAll();
        self::$ids = array();
        self::$names = array();
    }


    public function testDbCreation() {
        $this->assertTrue(file_exists(DbGoodsParams::dbFileName));
        $this->assertTrue(file_exists(DbOrdersParams::dbFileName));
    }

    public function testInsertToDbGoodsMethod() {
        // Проверить, имеются ли вообще данные в базе

        $rows = self::$db->getAllData();

        // Проверить только одно значение
        $isExists = self::checkExistance($rows, 'ПК1');
        $this->assertTrue($isExists, 'Insert operation error');
    }

    public function testSelectById() {
        // выборка должна работать для одной записи

        $rows = self::$db->getDataByIds(array(self::$ids[0]));
        $this->assertTrue(self::checkExistance($rows, self::$names[0]),
            'Select by one id operation error');

        // и для нескольких записей тоже
        $rows = self::$db->getDataByIds(self::$ids);

        $this->assertTrue(self::checkExistance($rows, self::$names[0]),
            'Select by two ids operation error');

        $this->assertTrue(self::checkExistance($rows, self::$names[1]),
            'Select by two ids operation error');

    }

    public function testDeleteById() {
        $rows = self::$db->getAllData();

        $this->assertTrue(self::checkExistance($rows, self::$names[0]),
            'Conditions is incorrect. Element must exists');

        self::$db->deleteGood(self::$ids[0]);

        $rows = self::$db->getAllData();

        $this->assertFalse(self::checkExistance($rows, self::$names[0]),
            'Element exists but must absents');
    }

    private static function checkExistance($array, $value) {
        $isExists = false;
        foreach($array as $row) {
            if (in_array($value, $row)) {
                $isExists = true;
                break;
            }
        }
        return $isExists;
    }
}