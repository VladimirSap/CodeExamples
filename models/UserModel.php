<?php
class UserModel {
    // функции для работы с записями пользователей
    // часть функций сгруппирована в отдельном классе
    // UsersStorageModel

    private $login = '';
    private $password = '';

    private $_result = false;
    // DO NOT USED: это сообщение нигде не выводится
    private $_message = '';

    function getLogin() { return $this->login; }
    function getPassword() { return $this->password; }
    function setLogin($data) { $this->login = $data; }
    function setPassword($data) { $this->password = $data; }

    function getMessage() { return $this->_message; }

    function LoginAction() {
       $result = UsersStorageModel::userExists($this->login);

        if($result) {
            list($login, $pw, $salt, $iterations) =
                explode (':', $result);
            if(UsersStorageModel::getHash($this->password, $salt) == $pw) {
                $this->_message = '';
                $this->startSession();
            } else {
                $this->_message = 'Неверная пара логин-пароль';
                $this->_result = false;
            }
        } else {

            $this->_message = 'Неверная пара логин-пароль';
            $this->_result = false;
        }
        return $this->_result;
    }

    function RegisterAction() {
        if (!UsersStorageModel::userExists($this->login)) {
            if($this->saveUser()) {
                $this->_message = 'Пользователь добавлен! Пожалуйста, войдите на сайт.';
                $this->_result = true;
            } else {
                $this->_message = 'Не могу зарегистрировать!';
                $this->_result = false;
            }
        } else {
            $this->_message = 'Пользователь с таким именем уже существует!';
            $this->_result = false;
        }

    }

    function saveUser() {
        $salt =
            str_replace('=', '', base64_encode(md5(microtime(). '1FD37EAA5ED9425683326EA68DCD0E59')));

        $result = UsersStorageModel::getHash($this->password, $salt);
        if (UsersStorageModel::saveUser($this->login, $result, $salt)) {
            return true;
        } else {
            return false;
        }

    }

    function startSession() {
        session_start();
        $_SESSION['user'] = $this->login;
    }

    function render() {
        ob_start();
        echo $this->_message;
        return ob_get_clean();
    }

    function getResult() {
        return $this->_result;
    }
}


