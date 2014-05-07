<?php

// Если заказы есть
if (sizeof($data) != 0) {
    printOrderByOrder($data);

} else {
    echo 'Заказов нет';
}

function printOrderByOrder($allOrders) {
    foreach($allOrders as $orders) {

        printOrderHeader($orders);

        $orderData = unserialize(base64_decode($orders['orderData']));

        $goodsInOrder = loadGoodsOfOrder($orderData);

        $totalPrice = 0;

        foreach($goodsInOrder as $res) {
            $id = $res['id'];
            $t = $res['title'];
            $q = $orderData[intval($id)];
            $p = $res['price'] * $q;
            $totalPrice += $p;

            printOrderDetails($t, $q, $p);
        }

        printFooter($totalPrice, $orders['id']);
    }
}

function loadGoodsOfOrder($orderData) {
    $dbData = DBModel::getInstance();

    DbParameters::setDbParams('DbGoodsParams');

    return $dbData->getDataByIds(array_keys($orderData));
}

function printOrderHeader($orders) {
    $dateTime = date('d.m.y H:i', $orders['datetime']);

    echo <<< LABEL
    <hr/>
    <p style="color: darkolivegreen"> Заказчик: {$orders['userName']}</p>
    <p style="color: darkolivegreen"> e-mail: {$orders['email']} </p>
    <p style="color: darkolivegreen> <em> Дата, время: " {$dateTime} "</em> </p>
LABEL;
}

function printOrderDetails($t, $q, $p) {
    echo <<<LABEL
               <h4 style="color: darkviolet"> Название: {$t} </h4>
               <p>
                   <em> Количество: {$q} шт. </em>
               </p>

               <p style="color: saddlebrown">
                    <em>
                       Цена: {$p} руб.
                   </em>
               </p>
LABEL;
}

function printFooter ($totalPrice, $id) {
    $deleteOrderLink = Registry::$rootLink. 'index.php/DB/deleteGood/DbOrdersParams';

    echo <<<LABEL
            <p>
                <b> Товаров на сумму: </b> {$totalPrice} руб. <br/>
                 <a href="{$deleteOrderLink}/{$id}"> Удалить заказ</a>
            </p>
LABEL;
}