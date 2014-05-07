<?php
// Глобальные переменные и методы
class Registry {

    public static $rootLink = null;

    public static $loginMessage = '';

    public static function setRootLink() {
        self::$rootLink = 'http://'.$_SERVER['HTTP_HOST']. '/';
    }

    public static function goToMainPage() {
        header('Location: '. self::$rootLink);
    }
}