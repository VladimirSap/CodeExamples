<?php
// Ссылки управления содержимым корзины
$increaseLink  = Registry::$rootLink . 'index.php/Basket/process/addToBasket';
$decreaseLink  = Registry::$rootLink . 'index.php/Basket/process/deleteFromBasket';
$makeOrderLink = Registry::$rootLink . 'index.php/DB/addInDb/DbOrdersParams';

if (is_array($goods)) {
    $totalPrice = 0;
    foreach($goods as $res) {

       $id = $res['id'];
       $q = $basket[$id];
       $t = $res['title'];
       $d = $res['description'];
       $p = $res['price'] * $q;
       $totalPrice += $p;

       echo <<<LABEL

           <h4 style="color: darkviolet"> Название: {$t} </h4>
           <p>
               <em> Количество: {$q} шт. </em>
               <a href="{$increaseLink}/$id">+</a>
               <a href="{$decreaseLink}/$id">-</a>
           </p>

           <p style="color: saddlebrown">
                <em>
                   Цена: {$p} руб.
               </em>
           </p>
           <hr/>
LABEL;

    } // конец foreach
    // Напечатать footer заказа
    echo <<<LABEL
    <p>
        <b> Товаров на сумму: </b> {$totalPrice} руб.
    </p>
    <!-- Текстовое поле с контактами -->
    <input type="text" id="email" value="Введите e-mail"/>
    <script>

        function addURL(element) {
            $(element).attr('href', function(){
                return this.href + '/' + $('#email').val();
            });
        }

    </script>

    <a onclick="addURL(this)" href="{$makeOrderLink}"> Заказать </a>
LABEL;
}
else {
    echo "<p> Ваша корзина пуста </p>";
}