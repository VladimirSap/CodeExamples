<?php

class DbGoods {

    private static $link, $linkTitle;

    public static function printSortedGoods($goods) {
        self::setLink();

        $link = self::$link;
        $linkTitle = self::$linkTitle;

    if ($goods != null) {
        foreach($goods as $res) {
            $id = $res['id'];
            $t = $res['title'];
            $dt = date("d.m.y",$res['datetime']);
            $d = $res['description'];
            $p = $res['price'];

            echo <<<LABEL
        <hr/>
        <h4 style="color: darkviolet"> Название: {$t} </h4>
        <p style="color: saddlebrown"> <em> Дата поступления: {$dt} </em> </p>
        <p>  <b> Описание: </b> <br/> {$d}   </p>
        <p style="color: saddlebrown"> <em> Цена: {$p} руб. </em> </p>
        <a href="{$link}/$id"> {$linkTitle} </a>
LABEL;
        }
    } else {
        echo "Информация отсутствует";
    }
   }

   private static function setLink() {
       $adminLink = Registry::$rootLink . 'index.php/DB/deleteGood/DbGoodsParams';
       $userLink  = Registry::$rootLink. 'index.php/Basket/process/addToBasket';

       if(isset($_SESSION['user'])) {
           if ($_SESSION['user'] == 'admin') {
               self::$link = $adminLink;
               self::$linkTitle = "Удалить";
           } else {
               self::$link = $userLink;
               self::$linkTitle = "В корзину";
           }
       } else {
           self::$link = $userLink;
           self::$linkTitle = "В корзину";
       }

   }
}
