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
# основые функции
require_once BASE_URI.'lib/functions.php';
# определение контроллера и действия - Controller Action Parameters
$arr = functions\defineCAP();
# page
functions\loadPage($smarty, $dbn, $arr['controller'], $arr['action'], $arr['params'], $arr['GET']);