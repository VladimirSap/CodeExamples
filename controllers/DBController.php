<?php
class DBController {

    private $model = null;

    public function __construct() {
        $this->model = DBModel::getInstance();
    }

    function clearStr($data) {
        return strip_tags(trim($data));
    }

    function clearInt($data) {
        return abs((int)$data);
    }

    private function getParams() {
        $f = FrontController::getInstance();
        return $f->getParams();
    }

    public function addInDb() {
        if(isset($_SERVER['REQUEST_METHOD']) == 'POST') {

            $params = $this->getParams();

            // Если это добавление нового товара админом
            if ($params[0] == 'DbGoodsParams') {
                require 'CheckAdmin.php';

                $this->addGoods();

            // Если это добавление нового заказа клиентом
            } else if ($params[0] == 'DbOrdersParams') {
                $this->addOrder();
            }
            // перенаправление на главную страницу для
            // обновления информации, очистки строки запроса
            Registry::goToMainPage();
        }
    }

    private function addGoods() {
        // Чтение данных из формы добавления нового товара
        // Объект можно удалить и формировать массив значений напрямую
        $t = $this->clearStr($_POST['title']);
        $c = $this->clearInt($_POST['category']);
        $d = $this->clearStr($_POST['description']);
        $p = $this->clearInt($_POST['price']);

        $good = new Goods($t, $c, $d, $p);

        // Сделать текущей таблицу товаров
        DbParameters::setDbParams('DbGoodsParams');
        // Перевести поля объекта в строчку и передать методу
        $this->model->insertData($good->getAsArray());

    }

    private function addOrder() {
            $params = $this->getParams();
            $b = new BasketModel();
            $basket = $b->getBasket();

            // Логин заказчика
            if (isset($_SESSION['user'])) {
                $userName = $_SESSION['user'];
            } else {
                $userName = 'Guest';
            }

            // Массив 'id' => quantity сериализуется для записи в столбец
            // orderData таблицы
            $orderData = base64_encode(serialize($basket));
            $b->initBasket(); // Очистить корзину
            $email = $params[1]; // контактная информация (передается в строчке запроса)
            DbParameters::setDbParams('DbOrdersParams');
            // Объект не создается
            $this->model->insertData(array($userName, $orderData, time(), $email));
    }

    public function deleteGood() {
        require 'CheckAdmin.php';

        $params = $this->getParams();

        if ($params[0] == DbGoodsParams::dbClassName ||
            $params[0] == DbOrdersParams::dbClassName) {

            DbParameters::setDbParams($params[0]);
            // второй параметр строчки запроса - id удаляемого товара
            $id = $params[1];
            if (is_int($id)) {
                $this->model->deleteGood($id);
            }
        }
        if ($params[0] == DbOrdersParams::dbClassName) {
            header('Location: '. Registry::$rootLink .'index.php/Forms/loadOrders/OrdersList.php');
        } else {
            Registry::goToMainPage();
        }
    }

    public function getAllGoods() {
        DbParameters::setDbParams('DbGoodsParams');
        return DbQueryElements::sortByCategory($this->model->getAllData());
    }

    public function render($output) {
        ob_start();
        include($output);
        return ob_get_clean();
    }
}