<?php
class BasketModel {
    public function __construct() {}

    // Параметры, передаваемые модели (например, id)
    private $params;

    public function setParams($params) { $this->params = $params; }
    public function getParams() { return $this->params; }

    public function getBasket() {
        if(!isset($_COOKIE['basket'])) {
            $this->initBasket();
        }

        $res = unserialize(base64_decode($_COOKIE['basket']));
        return $res;
    }

    public function initBasket() {
        $basket = array();
        $this->saveBasket($basket);
    }

    public function saveBasket($b) {
        $basket = base64_encode(serialize($b));
        setcookie('basket', $basket, time() + 3600, '/');
    }

    public function addToBasket() {
        $basket = $this->getBasket();

        // Ключи массива корзины - id товара.
        // Значения - количество товара.
        if(is_numeric($this->params)) {
            // Если товар уже был выбран однажды, то увеличить
            // его количество
            // Новый товар НЕ добавляется в корзину
            if (array_key_exists($this->params,$basket)) {
                $basket[$this->params]++;
            } else {
                // Если выбран впервые, то его кол-во равно 1
                // Новый товар добавляется в корзину
                $basket[$this->params] = 1;
            }
            $this->saveBasket($basket);
            // На главную страницу
            Registry::goToMainPage();
        }
    }

    public function deleteFromBasket() {
        $basket = $this->getBasket();

        if(is_numeric($this->params)) {
            if ($basket[$this->params] > 1) {
                $basket[$this->params]--;
            } else {
                unset($basket[$this->params]);
            }
            $this->saveBasket($basket);
            Registry::goToMainPage();
        }
    }

    public function clearBasket() {
        setcookie('basket', '', time() - 3600, '/');
    }

    public function getGoodsByBasket($basket) {
        // keys - id товара, values - количество каждого товара
        $keys =  array_keys($basket);

        $db = DBModel::getInstance();
        DbParameters::setDbParams('DBGoodsParams');
        return $db->getDataByIds($keys);
    }
}