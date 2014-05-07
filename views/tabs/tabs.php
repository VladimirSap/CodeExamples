<?
$location = '/views/tabs/';
?>

    <link type="text/css" rel="stylesheet" href="<?=$location ."style.css?version=51";?>"/>
    <script src="<?=$location?>jquery.min.js"></script>
    <script src="<?=$location?>script.js"> </script>
    <script src="<?=$location?>jquery.cookie.js"> </script>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <div class="tabContainer">
        <ul class="tabs">
            <li id = "tabH1" class ="tab-link current" content-tab="tab1"> Настольные ПК </li>
            <li id = "tabH2" class ="tab-link" content-tab="tab2"> Ноутбуки </li>
            <li id = "tabH3" class ="tab-link"content-tab="tab3"> Смартфоны </li>
            <li id = "tabH4" class ="tab-link"content-tab="tab4"> Корзина </li>
        </ul>
        <div id = "tab1" class="tab-content current">
            <? DBGoods::printSortedGoods($goods[1]);?>
        </div>
        <div id = "tab2" class="tab-content">
            <? DBGoods::printSortedGoods($goods[2]);?>
        </div>
        <div id = "tab3" class="tab-content">
            <? DBGoods::printSortedGoods($goods[3]);?>
        </div>
        <div id = "tab4" class="tab-content">
            <?=$output?>
        </div>
</div> <!-- End of tab container-->
