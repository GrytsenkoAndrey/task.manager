<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 27.12.2019
 * Time: 16:58
 */
define('DB_USER', 'myuser');
define('DB_PASS', 'myuserpass');
define('HOST', 'localhost');
define('DB_NAME', 'skillkurs1');
define('BASE_URI', $_SERVER['DOCUMENT_ROOT'] .'/'); // Путь к корню проекта
define('BASE_URL', '/'); // 'http://'.$_SERVER['SERVER_NAME'] .'/'); // Путь к корню сайта
define('SITE_DATE_FORMAT_SHORT', 'd.m.Y');
define('SITE_DATE_FORMAT_LONG', 'd.m.Y H:i:s');
define('CONTACT_EMAIL', 'test@test.dp');
define('PATH_PREFIX', 'controllers/');
define('PATH_POSTFIX', 'Controller.php');
// menu array
define ('TOP_MENU', [
    [
        'title' => 'Главная',
        'path' => 'index/all/',
        'active' => '',
    ],
    [
        'title' => 'Новинки',
        'path' => '?new=on',
        'active' => '',
    ],
    [
        'title' => 'Sale',
        'path' => '?sale=on',
        'active' => '',
    ],
    [
        'title' => 'Доставка',
        'path' => 'delivery/index/',
        'active' => '',
    ],
]);
define ('FOOTER_MENU', [
    [
        'title' => 'Главная',
        'path' => 'index/all/',
        'active' => '',
    ],
    [
        'title' => 'Новинки',
        'path' => '?new=on',
        'active' => '',
    ],
    [
        'title' => 'Sale',
        'path' => '?sale=on',
        'active' => '',
    ],
    [
        'title' => 'Войти',
        'path' => 'user/login/',
        'active' => '',
    ],
]);
// для админов-модераторов
define ('OP_MENU', [
    [
        'title' => 'Главная',
        'path' => 'index/all/',
        'active' => '',
    ],
    [
        'title' => 'Товары',
        'path' => 'admin/index/',
        'active' => '',
    ],
    [
        'title' => 'Заказы',
        'path' => 'admin/order',
        'active' => '',
    ],
    [
        'title' => 'Доставка',
        'path' => 'delivery/index/',
        'active' => '',
    ],
    [
        'title' => 'Выйти',
        'path' => 'user/logout/',
        'active' => '',
    ],
]);

// Smarty
// default template
$template = 'default';
// path
define('TEMPLATE_PREFIX', "views/{$template}/");
define('TEMPLATE_POSTFIX', '.tpl');
// path in www
define('TEMPLATE_WEB_PATH', "/templates/{$template}/");
// init Smarty
require_once 'lib/Smarty/libs/Smarty.class.php';
$smarty = new Smarty();

$smarty->setTemplateDir(TEMPLATE_PREFIX);
$smarty->setCompileDir('tmp/smarty/templates_c');
$smarty->setCacheDir('tmp/smarty/cache');
$smarty->setConfigDir('lib/Smarty/configs');

$smarty->assign('templateWebPath', TEMPLATE_WEB_PATH);
// end Smarty