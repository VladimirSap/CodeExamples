<?php

require 'CheckAdmin.php';

$rootLink = Registry::$rootLink. 'index.php/Forms/';
$addGoodsLink = $rootLink . 'process/AddMaterial.php';
$ordersLink  = $rootLink . 'loadOrders/OrdersList.php';

?>
<h4> Меню администратора </h4>
<ul>
    <li> <a href="<?=$addGoodsLink ?>">Добавить товар</a> </li>
    <li> <a href="<?=$ordersLink ?>">Список заказов</a> </li>
</ul>
