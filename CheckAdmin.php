<?php

// include сверху страниц, которые предназначены только для администратора
session_start();
if ($_SESSION['user'] != 'admin') {
    Registry::goToMainPage();
    exit;
}