<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 23.03.2020
 * Time: 16:57
 */
require_once 'models/IndexModel.php';
require_once 'models/CategoryModel.php';
/**
 * главная страница
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function allAction($smarty, $dbn, array $params, array $get)
{
    filter($smarty, $dbn, $params, $get, 'all');
}

/**
 * главная страница c women
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function womenAction($smarty, $dbn, array $params, array $get)
{
    filter($smarty, $dbn, $params, $get, 'women');
}

/**
 * главная страница c men
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function menAction($smarty, $dbn, array $params, array $get)
{
    filter($smarty, $dbn, $params, $get, 'men');
}

/**
 * главная страница c children
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function childrenAction($smarty, $dbn, array $params, array $get)
{
    filter($smarty, $dbn, $params, $get, 'children');
}

/**
 * главная страница c accessories
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function accessoriesAction($smarty, $dbn, array $params, array $get)
{
    filter($smarty, $dbn, $params, $get, 'accessories');
}

/**
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 * @param string $filter - раздел
 */
function filter($smarty, $dbn, array $params, array $get, string $filter)
{
    $_SESSION['params'] = $params;
    $_SESSION['get'] = $get;
    $_SESSION['filter'] = $filter;

    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';
    # перебираем массив для форматирования цены
    $rsProducts = formatPriceInData(selectProdByCategory($dbn, $filter, $params, $get));
    # categories
    $rsCategories = getAllCategories($dbn);
    # добавляем класс для активного элемента
    for ($i = 0, $qnt = count($rsCategories); $i < $qnt; $i++) {
        $rsCategories[$i]['active'] = $rsCategories[$i]['category'] == $filter ? ' active' : '';
    }
    # total found
    $arrT = selectAllProd($dbn, $filter, $get);
    $rsQuantity = selectProdQnt($arrT);

    # pagination
    $rsPag = functions\pagination($arrT);
    # menu
    $menu = functions\getMenu(TOP_MENU, functions\defineCAP());
    $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
    $footerMenu = functions\getMenu($fMenu, functions\defineCAP());
    # activeUser
    $activeUser = $_SESSION['user_name'] ?? '';
    # new & sale
    $newChecked = isset($get['new']) ? ' checked' : '';
    $saleChecked = isset($get['sale']) ? ' checked' : '';
    # price
    $minPrice = isset($get['min']) ? number_format($get['min'], 0, '', ' ') : '350';
    $maxPrice = isset($get['max']) ? number_format($get['max'], 0, '', ' ') : '32 000';

    $smarty->assign('pageTitle', 'Главная');
    $smarty->assign('infoMsg', $infoMsg);
    $smarty->assign('menu', $menu);
    $smarty->assign('rsPag', $rsPag);
    $smarty->assign('rsCategories', $rsCategories);
    $smarty->assign('quantity', $rsQuantity);
    $smarty->assign('activeUser', $activeUser);
    $smarty->assign('rsProducts', $rsProducts);
    $smarty->assign('footerMenu', $footerMenu);
    $smarty->assign('newChecked', $newChecked);
    $smarty->assign('saleChecked', $saleChecked);
    $smarty->assign('minprice', $minPrice);
    $smarty->assign('maxprice', $maxPrice);
    functions\loadTemplate($smarty, 'head');
    functions\loadTemplate($smarty, 'index');
    functions\loadTemplate($smarty, 'footer');

    $_SESSION['infoMsg'] = '';
}

/**
 * ajax - сортировка
 */
function orderSortAction($smarty, $dbn, array $params, array $get)
{
    $order = (empty($_POST['order'])) ? 'id' : $_POST['order'];
    $dir = (empty($_POST['dir'])) ? 'DESC' : $_POST['dir'];

    $filter = functions\defineCAP();
    $rsProducts = formatPriceInData(selectProdByCategory($dbn, $_SESSION['filter'], $_SESSION['params'], $_SESSION['get'], $order, $dir));
    $smarty->assign('rsProducts', $rsProducts);
    functions\loadTemplate($smarty, 'findex');
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
    $menu = functions\getMenu(TOP_MENU, functions\parseUri());
    $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
    $footerMenu = functions\getMenu($fMenu, functions\parseUri());
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
