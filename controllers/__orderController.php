<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 29.03.2020
 * Time: 19:19
 */
require_once 'models/OrderModel.php';
require_once 'models/IndexModel.php';
/**
 * добавление товара в заказы | ajax |
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function addordAction($smarty, $dbn, array $params, array $get)
{
    addProdToOder($dbn, $_POST);
}

/**
 * изменяем статус заказа | ajax |
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function updstatusAction($smarty, $dbn, array $params, array $get)
{
    updOrderStatus($dbn, $_POST);
}

/**
 * отображение списка заказов
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function orderAction($smarty, $dbn, array $params, array $get)
{
    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';
    if(functions\checkAdmin() || functions\checkModer()) {
        # menu
        $menu = functions\getMenu(TOP_MENU, functions\defineCAP());
        $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
        $footerMenu = functions\getMenu($fMenu, functions\defineCAP());
        # activeUser
        $activeUser = $_SESSION['user_name'] ?? '';
        # перебираем массив для форматирования цены
        $rsOrders = formatPriceInData(selectOrders($dbn, $params));
        # pagination
        $rsPag = functions\pagination($rsOrders);

        $smarty->assign('pageTitle', 'каталог');
        $smarty->assign('infoMsg', $infoMsg);
        $smarty->assign('menu', $menu);
        $smarty->assign('activeUser', $activeUser);
        $smarty->assign('footerMenu', $footerMenu);
        $smarty->assign('rsOrders', $rsOrders);
        $smarty->assign('rsPag', $rsPag);
        functions\loadTemplate($smarty, 'head');
        functions\loadTemplate($smarty, 'orders');
        functions\loadTemplate($smarty, 'footer');

        $_SESSION['infoMsg'] = '';
    } else {
        $_SESSION['infoMsg'] = "<div class='alert alert-danger'>У Вас нет прав для доступа к данному разделу</div>";
        header("Location: /");
        exit();
    }
}