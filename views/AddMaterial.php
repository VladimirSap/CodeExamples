<?
require 'CheckAdmin.php';
$action = Registry::$rootLink.'index.php/DB/addInDb/DbGoodsParams';
?>
<div style="font-size: 30px">

<h3> Добавить товар</h3>
<form action = "<?php echo $action ?>" method="POST">
    <label for="title"> Название </label>
    <input type="text" name="title" value="Заголовок"/>
    <label for="category"> Категория </label>
    <select name="category">
        <option value="1"> Настольный ПК </option>
        <option value="2"> Ноутбук </option>
        <option value="3"> Смартфон </option>
    </select> <br/>
    <label for="description"> Описание </label>
    <textarea name="description" cols="18" rows="5">
    </textarea> <br/>
    <label for="price"> Цена, руб. </label>
    <input type="text" name="price" value="500"/>
    <input type="submit" value="Добавить">
</form>
</div>