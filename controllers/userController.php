<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 23.03.2020
 * Time: 16:57
 */
require_once 'models/UserModel.php';
/**
 * главная страница
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function loginAction($smarty, $dbn, array $params, array $get)
{
    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';
    functions\checkUserAuth();

    if ($_POST) {
        login($dbn, $_POST);
    } else {
        # menu
        $menu = functions\getMenu(TOP_MENU, functions\parseUri());
        $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
        $footerMenu = functions\getMenu($fMenu, functions\parseUri());
        # activeUser
        $activeUser = $_SESSION['user_name'] ?? '';

        $smarty->assign('pageTitle', 'Авторизация');
        $smarty->assign('infoMsg', $infoMsg);
        $smarty->assign('activeUser', $activeUser);
        $smarty->assign('menu', $menu);
        $smarty->assign('footerMenu', $footerMenu);
        functions\loadTemplate($smarty, 'head');
        functions\loadTemplate($smarty, 'login');
        functions\loadTemplate($smarty, 'footer');

        $_SESSION['infoMsg'] = '';
    }
}

/**
 * выход пользователя из системы
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function logoutAction($smarty, $dbn, array $params, array $get)
{
    logout($dbn);
    header('Location: '. BASE_URL);
}

/**
 * регистрация нового пользователя
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function registerAction($smarty, $dbn, array $params, array $get)
{
    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';
    functions\checkUserAuth();

    if ($_POST) {
        registration($dbn, $_POST);
    } else {
        # menu
        $menu = functions\getMenu(TOP_MENU, functions\parseUri());
        $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
        $footerMenu = functions\getMenu($fMenu, functions\parseUri());
        # activeUser
        $activeUser = $_SESSION['user_name'] ?? '';

        $smarty->assign('pageTitle', 'Авторизация');
        $smarty->assign('infoMsg', $infoMsg);
        $smarty->assign('activeUser', $activeUser);
        $smarty->assign('menu', $menu);
        $smarty->assign('footerMenu', $footerMenu);
        functions\loadTemplate($smarty, 'head');
        functions\loadTemplate($smarty, 'register');
        functions\loadTemplate($smarty, 'footer');

        $_SESSION['infoMsg'] = '';
    }
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
