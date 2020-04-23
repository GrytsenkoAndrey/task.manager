<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 26.03.2020
 * Time: 21:17
 */
# models
require_once 'models/ProductModel.php';
require_once 'models/CategoryModel.php';
require_once 'models/IndexModel.php';
/**
 * добавление нового товара
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function addAction($smarty, $dbn, array $params, array $get)
{
    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';
    if(!functions\checkAdmin()) {
        $_SESSION['infoMsg'] = "<div class='alert alert-danger'>У Вас нет прав для доступа к данному разделу</div>";
        header("Location: /");
        exit();
    }

    if($_POST) {
        addProd($dbn, $_POST);
        $bodyTplName = 'add_ok';
    } else {
        $bodyTplName = 'add';
        $_SESSION['infoMsg'] = '';
    }

    # menu
    $menu = functions\getMenu(TOP_MENU, functions\defineCAP());
    $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
    $footerMenu = functions\getMenu($fMenu, functions\defineCAP());
    # activeUser
    $activeUser = $_SESSION['user_name'] ?? '';

    $smarty->assign('pageTitle', 'добавление товара');
    $smarty->assign('menu', $menu);
    $smarty->assign('activeUser', $activeUser);
    $smarty->assign('footerMenu', $footerMenu);
    functions\loadTemplate($smarty, 'head');
    functions\loadTemplate($smarty, $bodyTplName);
    functions\loadTemplate($smarty, 'footer');
}

/**
 * список товаров
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function indexAction($smarty, $dbn, array $params, array $get)
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
        $rsProducts = formatPriceInData(selectProdByCategory($dbn, 'all', $params, $get));
        # pagination
        $rsPag = functions\pagination($rsProducts);

        $smarty->assign('pageTitle', 'каталог');
        $smarty->assign('menu', $menu);
        $smarty->assign('activeUser', $activeUser);
        $smarty->assign('footerMenu', $footerMenu);
        $smarty->assign('rsProducts', $rsProducts);
        $smarty->assign('rsPag', $rsPag);
        functions\loadTemplate($smarty, 'head');
        functions\loadTemplate($smarty, 'products');
        functions\loadTemplate($smarty, 'footer');

        $_SESSION['infoMsg'] = '';
    } else {
        $_SESSION['infoMsg'] = "<div class='alert alert-danger'>У Вас нет прав для доступа к данному разделу</div>";
        header("Location: /");
        exit();
    }
}

/**
 * страница редактирования товара
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function editAction($smarty, $dbn, array $params, array $get)
{
    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';

    if(functions\checkAdmin() || functions\checkModer()) {
        if ($_POST) {
            updateProduct($dbn, $_POST);
            # menu
            $menu = functions\getMenu(TOP_MENU, functions\defineCAP());
            $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
            $footerMenu = functions\getMenu($fMenu, functions\defineCAP());
            # activeUser
            $activeUser = $_SESSION['user_name'] ?? '';

            $smarty->assign('pageTitle', 'редактирование товара');
            $smarty->assign('menu', $menu);
            $smarty->assign('activeUser', $activeUser);
            $smarty->assign('footerMenu', $footerMenu);
            functions\loadTemplate($smarty, 'head');
            functions\loadTemplate($smarty, 'add_ok');
            functions\loadTemplate($smarty, 'footer');
        } else {
            # menu
            $menu = functions\getMenu(TOP_MENU, functions\defineCAP());
            $fMenu = isset($_SESSION['user_id']) ? OP_MENU : FOOTER_MENU;
            $footerMenu = functions\getMenu($fMenu, functions\defineCAP());
            # activeUser
            $activeUser = $_SESSION['user_name'] ?? '';
            $rsProduct = selectProductById($dbn, $params);
            $rsCategories = getAllCategories($dbn);
            $f = array_shift($rsCategories);
            # временно сохраняем значение logo
            $_SESSION['pr-logo'] = $rsProduct['logo'];

            $smarty->assign('pageTitle', 'редактирование товара');
            $smarty->assign('menu', $menu);
            $smarty->assign('activeUser', $activeUser);
            $smarty->assign('footerMenu', $footerMenu);
            $smarty->assign('rsProduct', $rsProduct);
            $smarty->assign('rsCategories', $rsCategories);
            functions\loadTemplate($smarty, 'head');
            functions\loadTemplate($smarty, 'edit');
            functions\loadTemplate($smarty, 'footer');

            $_SESSION['infoMsg'] = '';
        }
    } else {
        $_SESSION['infoMsg'] = "<div class='alert alert-danger'>У Вас нет прав для доступа к данному разделу</div>";
        header("Location: /");
        exit();
    }
}

/**
 * удаление товара из каталога
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function deleteAction($smarty, $dbn, array $params, array $get)
{
    delProductById($dbn, $_POST['id']);
}
