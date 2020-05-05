<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 27.12.2019
 * Time: 17:15
 */
session_start();
# файл настроек
require_once 'nic/cnf.inc.php';
# class изменения диапазона цен
require_once BASE_URI.'nic/config.php';
# подключение к базе данных
require_once BASE_URI.'nic/mysql.inc.php'; 
$dbn = nic\getConn();
if ($dbn == null) {
    echo "Error during connection to DB. Call @admin";
    exit();
}
# основые функции
require_once BASE_URI.'lib/functions.php';
# определение контроллера и действия - Controller Action Parameters
$arr = functions\defineCAP();
# проверка пользователя
if ($arr['controller'] == 'admin') {
    if(!functions\checkAdmin() && !functions\checkModer()) {
        $_SESSION['infoMsg'] = "<div class='alert alert-danger'>Для доступа к разделу необходимо авторизоваться</div>";
        header("Location: /user/login/");
        exit();
    }
}
# page
functions\loadPage($smarty, $dbn, $arr['controller'], $arr['action'], $arr['params'], $arr['GET']);