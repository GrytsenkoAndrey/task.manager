<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 28.03.2020
 * Time: 16:48
 */
require_once 'models/DeliveryModel.php';
/**
 * главная страница
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function indexAction($smarty, $dbn, array $params, array $get)
{
    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';
    # menu
    $menu = functions\getMenu(TOP_MENU, functions\defineCAP());
    $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
    $footerMenu = functions\getMenu($fMenu, functions\defineCAP());
    # activeUser
    $activeUser = $_SESSION['user_name'] ?? '';

    $smarty->assign('pageTitle', 'доставка');
    $smarty->assign('infoMsg', $infoMsg);
    $smarty->assign('menu', $menu);
    $smarty->assign('activeUser', $activeUser);
    $smarty->assign('footerMenu', $footerMenu);
    functions\loadTemplate($smarty, 'head');
    functions\loadTemplate($smarty, 'delivery');
    functions\loadTemplate($smarty, 'footer');

    $_SESSION['infoMsg'] = '';
}

/**
 * страница не найдена
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function nfoundAction($smarty, $dbn, array $params, array $get)
{
    $_SESSION['params'] = $params;
    $_SESSION['get'] = $get;

    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';

    # menu
    $menu = functions\getMenu(TOP_MENU, functions\defineCAP());
    $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
    $footerMenu = functions\getMenu($fMenu, functions\defineCAP());
    # activeUser
    $activeUser = $_SESSION['user_name'] ?? '';

    $smarty->assign('pageTitle', 'Главная');
    $smarty->assign('infoMsg', $infoMsg);
    $smarty->assign('menu', $menu);
    $smarty->assign('activeUser', $activeUser);
    $smarty->assign('footerMenu', $footerMenu);
    functions\loadTemplate($smarty, 'head');
    functions\loadTemplate($smarty, 'notfound');
    functions\loadTemplate($smarty, 'footer');

    $_SESSION['infoMsg'] = '';
}
