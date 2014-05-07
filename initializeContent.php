<?php
// Настройки
header('Content-Type: text/html; charset=utf-8');
require_once 'autoload.php';

// Задать глобальные переменные
Registry::setRootLink();

// Ссылки
$cssLink = Registry::$rootLink .'stylesheet.css';

$rootLink = Registry::$rootLink. 'index.php/';
$loginLink = $rootLink . 'Forms/process/LoginView.php';
$logoutLink = $rootLink . 'Login/logout';

// Указать тип используемой БД
// MySQL или SQLite (по умолчанию)
DbParameters::setDbType('SQLite');

// Контент главной страницы
session_start();
if (isset($_SESSION['user'])) {
    $userName = $_SESSION['user'];
    $link = "<a href=" .$logoutLink."> Выход </a>";
} else {
    $userName = "Гость";
    $link = '';
}
$greeting = "Приветствую Вас, $userName, на моем демо сайте! $link";
$instruction = "Добавьте товары в корзину, просмотрите товары в корзине. Закажите их. ";
$instruction .= "Зайдите как администратор (пароль уже введен для удобства). ";
$instruction .= "Просмотрите заказы. Добавьте новые товары. ";
$instruction .= "Зарегистрируйте нового пользователя. ";

// Чтение корзины, представленной в куках
// Если куки еще нет, то она создается
$basketController = new BasketController();
$output = $basketController->getBasket();

// Загрузка товаров, отсортированных по категориям
// через Controller
$dbController = new DBController();
$goods = $dbController->getAllGoods();

// Обработка текущего запроса (если он есть).
// При каждом reload главный контроллер парсит адресную строчку
// если запроса нет, то вызывается IndexController (заглушка).
// В нем можно разместить управление содержанием страницы index.php

$front = FrontController::getInstance();
$front->route();
$menu = $front->getBody();

// Определение содержания правого меню
if (!isset($_SESSION['user'])) {
    $show = 'views/LoginView.php';
} else {
    if ($_SESSION['user'] == 'admin') {
        $show = '/views/admin.php';
    } else {
        $menu = 'Меню пользователя в разработке';
    }
}