<?php
class DBModel {
    // Singleton

    // В проекте присутствуют 2 таблицы - goods (список товаров) и orders
    // (список заказов).
    // Параметры для каждого типа БД и таблиц хранятся в классах
    // DbGoodsParams/DbOrdersParams.
    // Для обращения к разным таблицам используется единая модель.
    // Для того, чтобы получить правильный набор параметров таблицы,
    // необходимо установить название соответствующего класса в качестве
    // текущего (метод DbParameters::setDbParams($tables)).
    // После этого осуществляется доступ к набору параметров, например, так:
    // DbParameters::getConstant('idAutoInc'). Таким образом, таблица, для которой
    // осуществляются запросы, выбирается динамически.
    // Таким образом, если требуется добавить дополнительный столбец к таблице,
    // то изменения необходимо вносить в файлах параметров, а не в модели

    private static $instance = null;
    public static function getInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __clone() {}

    // задача __construct - создать БД и объект  PDO для соединения с ней
    // либо только объект PDO, если БД уже существует
    private function __construct() {
        try {
            DbCreation::connectDb();

        } catch (Exception $e) {
            echo 'Ошибка во время соединения с БД: ' .$e->getMessage();
            exit();
        }
    }

    public function insertData($varArray) {
        // Если id генерируется автоматически, то необходимо вырезать id из
        // массива данных, которые будут добавлены в таблицу
        // DbParameters::getConstant('idAutoInc') - если true, то убрать
        // параметр id для вставки в таблицу.
        // VarSet - массив, содержащий названия столбцов и их параметры (тип значения)

        $varLine = DbQueryElements::prepareVarSet(array_keys(
                DbParameters::getVarSet(DbParameters::$dbType)),
                                            DbParameters::getConstant('idAutoInc'));

        // то же - для определения количества знаков вопроса
        // для подготовленного запроса
        $qMarks = DbQueryElements::getQMarks();

        $tableName = DbParameters::getConstant('tableName');

        // Универсальный для любой таблицы подготовленный запрос
        $sql = "INSERT INTO $tableName($varLine)
                        VALUES($qMarks)";

        DbExecElements::prepareAndExecute($sql, $varArray);
    }

    public function getAllData() {
        $varLine =
            DbQueryElements::prepareVarSet(array_keys(
                                DbParameters::getVarSet(DbParameters::$dbType)), false);

        $tableName = DbParameters::getConstant('tableName');

        $sql = "SELECT $varLine FROM $tableName ORDER BY datetime DESC";

        $result = DbExecElements::executeQuery($sql);
        return $result->fetchALL(PDO::FETCH_ASSOC);
    }

    public function getDataByIds($ids) {
        // в строчку через запятые названия искомых столбцов
        $varLine =
            DbQueryElements::prepareVarSet(array_keys(
                DbParameters::getVarSet(DbParameters::$dbType)), false);
        // то же для поступивших id
        $idToQuery = DbQueryElements::prepareVarSet($ids, false);
        $tableName = DbParameters::getConstant('tableName');

        $sql = "SELECT $varLine FROM $tableName
        WHERE id IN($idToQuery) ORDER BY datetime DESC";

        $result = DbExecElements::executeQuery($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    function deleteGood($id) {
        $tableName = DbParameters::getConstant('tableName');
        $sql = "DELETE from $tableName WHERE id = ?";

        DbExecElements::prepareAndExecute($sql, array($id));
    }

    public function deleteAll() {
        $tableName = DbParameters::getConstant('tableName');
        $sql = "DELETE from $tableName";
        DbExecElements::executeQuery($sql);
    }
}