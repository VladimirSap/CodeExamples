<?
$action = Registry::$rootLink.'index.php/Login/process';

echo <<<LABEL

<!-- Форма входа на сайт -->
<form action = "$action" method = "POST">
    <label for="userName"> Логин: </label>
    <input type="text" name="userName" value="admin"/> <br/>
    <label for="userPassword"> Пароль: </label>
    <input type="password" name="userPassword" value="123" /> <br/> <br/>
    <input type="submit" name="btnLogin" value="Войти"/>
    <input type="submit" name="btnRegister" value="Зарегистрироваться"/>
</form>

LABEL;

?>