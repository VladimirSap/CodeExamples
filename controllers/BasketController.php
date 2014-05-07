<?php
class BasketController {

    private $model = null;

    public function __construct() {
        $this->model = new BasketModel();
    }

    // Часть запросов обрабатывается через process
    // через обработку параметров.
    // Часть запросов - указанием имен конкретных методов в запросе.
    // Все клики на ссылки проходят через FrontController.
    public function process() {
        $front = FrontController::getInstance();
        $params = $front->getParams();

        // параметры передаются в строке запроса
        $method = $params[0];
        // Вариант без использования рефлексии
        if(method_exists($this->model, $method)) {
            // При добавлении товара в корзину
            // в качестве параметра также передается id товара
            if (array_key_exists(1, $params)) {
                $this->model->setParams($params[1]);
            }
            $this->model->$method();
        }
    }

    public function getBasket() {
        $basket = $this->model->getBasket();

        // если в корзине что-то уже есть
        if (sizeof($basket) != 0 && $basket != false) {
            $goods = $this->model->getGoodsByBasket($basket);
        } else {
            // товаров нет. View выведет сообщение, что корзина пуста
            $goods = null;
        }
        return $this->render($goods, $basket);
    }

    public function render($goods, $basket) {
        // Принимаемые переменные используются во view
        ob_start();
        include('views/Basket.php');
        return ob_get_clean();
    }
}