<?php
// Используется в приложении и для unit-тестов

session_start();
set_include_path(get_include_path()
    .PATH_SEPARATOR.'controllers'
    .PATH_SEPARATOR.'models'
    .PATH_SEPARATOR.'views'
    .PATH_SEPARATOR.'Data'
    .PATH_SEPARATOR.'models/DbModelElements'
    .PATH_SEPARATOR.'models/DbModelElements/Parameters'
);

function __autoload($class) {
    require_once($class.'.php');
}