<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 03.04.2020
 * Time: 13:07
 */
require_once 'models/UserModel.php';
require_once 'models/OrderModel.php';
require_once 'models/ProductModel.php';
require_once 'models/CategoryModel.php';
require_once 'models/IndexModel.php';
/**
 * работа с админпанелью
 */

# --  product
/**
 * главная страница - страница отображения заказов
 *
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
        $rsPag = functions\pagination(selectAllProd($dbn, 'all', []));

        $smarty->assign('pageTitle', 'каталог');
        $smarty->assign('menu', $menu);
        $smarty->assign('activeUser', $activeUser);
        $smarty->assign('footerMenu', $footerMenu);
        $smarty->assign('rsProducts', $rsProducts);
        $smarty->assign('rsPag', $rsPag);
        $smarty->assign('infoMsg', $infoMsg);
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

/** AJAX
 * загрузка изображения
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
 function uploadAction($smarty, $dbn, array $params, array $get)
 {
     $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';
     if(!functions\checkAdmin()) {
         $_SESSION['infoMsg'] = "<div class='alert alert-danger'>У Вас нет прав для доступа к данному разделу</div>";
         header("Location: /");
         exit();
     }

     $title = $_POST['title'];

     // Decode base64 data
     list($type, $data) = explode(';', $_POST['file']);
     list(, $data) = explode(',', $data);
     $file_data = base64_decode($data);

     // Get file mime type
     $finfo = finfo_open();
     $file_mime_type = finfo_buffer($finfo, $file_data, FILEINFO_MIME_TYPE);

     // File extension from mime type
     if ($file_mime_type == 'image/jpeg' || $file_mime_type == 'image/jpg') {
         $file_type = 'jpeg';
     } elseif ($file_mime_type == 'image/png') {
         $file_type = 'png';
     } elseif ($file_mime_type == 'image/gif') {
         $file_type = 'gif';
     } else {
         $file_type = 'other';
     }

     // Validate type of file
     if(in_array($file_type, [ 'jpeg', 'png', 'gif', 'jpg' ])) {
         // Set a unique name to the file and save
         $file_name = TEMPLATE_WEB_PATH . 'img/products/' . pathinfo($file_data, PATHINFO_FILENAME) . $file_type; //uniqid() . '.' . $file_type;
         file_put_contents($file_name, $file_data);
     }
     else {
         trigger_error('Error : Only JPEG, PNG & GIF allowed');
     }

     echo "uploaded $file_mime_type :: $file_name";
 }
/**
 * добавление нового товара
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function addprodAction($smarty, $dbn, array $params, array $get)
{
    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';
    if(!functions\checkAdmin()) {
        $_SESSION['infoMsg'] = "<div class='alert alert-danger'>У Вас нет прав для доступа к данному разделу</div>";
        header("Location: /");
        exit();
    }
/*
    if($_POST) {
functions\d($_POST);
        # check !empty
        if (empty($_POST['product-name']) ||
            empty($_POST['product-price']) ||
            empty($_POST['product-photo']) ||
            empty($_POST['product-qnt']) ||
            empty($_POST['category']))
        {
            $_SESSION['infoMsg'] = "<div class='alert alert-danger'>Все поля должны быть заполнены!</div>";
            header("Location: /admin/addprod/");
            exit();
        }

        addProd($dbn, $_POST);
        $bodyTplName = 'add_ok';
    } else {   */
        $bodyTplName = 'add';
        $_SESSION['infoMsg'] = '';
    /*}*/

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
    $smarty->assign('infoMsg', $infoMsg);
    functions\loadTemplate($smarty, 'head');
    functions\loadTemplate($smarty, $bodyTplName);
    functions\loadTemplate($smarty, 'footer');
}

/**
 * страница редактирования товара
 *
 * @param object $smarty
 * @param resource $dbn
 * @param array $params
 * @param array $get - $_GET
 */
function editprodAction($smarty, $dbn, array $params, array $get)
{
    $infoMsg = isset($_SESSION['infoMsg']) ? $_SESSION['infoMsg'] : '';

    if(functions\checkAdmin() || functions\checkModer()) {
        if ($_POST) {
            # check !empty
            if (empty($_POST['product-name']) ||
                empty($_POST['product-price']) ||
                empty($_POST['product-photo']) ||
                empty($_POST['product-qnt']) ||
                empty($_POST['category']))
            {
                $_SESSION['infoMsg'] = "<div class='alert alert-danger'>Все поля должны быть заполнены!</div>";
                header("Location: /admin/index/");
                exit();
            }
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
            $smarty->assign('infoMsg', $infoMsg);
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
function delprodAction($smarty, $dbn, array $params, array $get)
{
    delProductById($dbn, $_POST['id']);
}

# --  order
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
        $rsPag = functions\pagination(getAllOrders($dbn), 15);

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
