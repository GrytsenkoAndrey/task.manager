<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 27.12.2019
 * Time: 17:58
 */
namespace functions;

/**
 * function to explode URI
 * @return array after explode
 *
 */
function parseUri() : array
{
    // check address
    $arrUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $arrUri = explode('/', $arrUri);

    return $arrUri;
}

/**
* функция определяет контроллер, действие, параметры
* если такие есть или присваивает значения по умолчанию
* 
* @return array 
*/
function defineCAP() : array
{
    # parse URI
    $aURI = parseUri();
    # output array
    $aOUT = [];
    $aOUT['controller'] = !empty($aURI[0]) ? trim(strip_tags($aURI[0])) : 'index';
    //$aOUT['action'] = !empty($aURI[1]) ? trim(strip_tags($aURI[1])) : 'index';
    /*
     * check action in case of chapter : visible OR admin
     *
     * если action не пуст - присваиваем значение из адресной строки
     * если пуст смотрим на контроллер
     * - для index  назначаем all
     * - для admin назначаем index
     */
    if (!empty($aURI[1])) {
        $aOUT['action'] = trim(strip_tags($aURI[1]));
    } else {
        $aOUT['action'] = $aOUT['controller'] == 'admin' ? 'index' : 'all';
    }
    # если количество элементов четное
    if (count($aURI) % 2 == 0 && count($aURI) > 2) {
        // начиная со сторого элемента и до количества 
        // ключ => значение пар
        $k = $v = [];
        for ($i = 2, $cnt = count($aURI); $i < $cnt; $i++) {
            if ($i % 2 == 0) {
                $k[] = $aURI[$i];
            } else {
                $v[] = $aURI[$i];
            }
        }
        $aOUT['params'] = array_combine($k, $v);
    } else {
        $aOUT['params'] = [];
    }
    # GET
    if (!empty($_GET)) {
        $aOUT['GET'] = $_GET;
    } else {
        $aOUT['GET'] = [];
    }

    return $aOUT;
}

/**
* формирование запрашиваемой страницы
* @param object
* @param string controllerName название контроллера
* @param string actionName название действия
* @param resource $db 
* @param array $params
* @param array $get
*/
function loadPage($smarty, $db, string $controllerName, string $actionName, array $params, array $get)
{
    require_once PATH_PREFIX. $controllerName .PATH_POSTFIX;

    if (function_exists($actionName . 'Action')) {
        $function = $actionName .'Action';
    } else {
        $function = 'nfoundAction';
    }
    $function($smarty, $db, $params, $get);
}

/**
* загрузка шаблона
* @param object
* @param string имя шаблона
*/
function loadTemplate($smarty, string $templateName)
{
    $smarty->display($templateName.TEMPLATE_POSTFIX);
}

/**
* функция отладки; останавливает работу программы и выводит
* значение переменной $value
* @param variant value переменная для вывода на страницу
*/
function d($value = null, int $die = 1)
{
    echo 'Debug: <br><pre>';
    print_r($value);
    echo '</pre>';

    if ($die): die(); endif;
}


/**
 * формирование меню
 */
    function getMenu(array $arrMenu, array $arrUri) : string
{
    if (!is_array($arrMenu)) {
        trigger_error('Input parameter has to be an array');
    }
    $arrUri[0] = empty($arrUri[0]) ? 'index' : $arrUri[0];
    $strMenu = '';
    foreach ($arrMenu as $k => $v) {
        if ($v['path'] == $arrUri[0]) {
            $v['active'] = ' active';
        }
        $strMenu .= "<li><a class='main-menu__item" . $v['active'] . "' href='" . BASE_URL . $v['path'] . "'>" . $v['title'] . "</a></li>\n";
    }
    return $strMenu;
}

/**
 * проверяем авторизован ли уже пользователь
 */
function checkUserAuth()
{
    # проверяем авторизован ли уже пользователь
    if (isset($_SESSION['user_id'])) {
        $_SESSION['infoMsg'] = '<p>Вы уже авторизованы</p>';
        header("Location: /");
        exit();
    }
}

/**
 * очистка входных данных
 * @param - array - исходный массив
 * @return - array - массив после обработки
 */
function clearData(array $data) : array
{
    $arr = [];
    if (is_array($data)) {
        foreach($data as $k => $v) {
            if (is_array($v)) {
                clearData($v);
            } else {
                $arr[$k] = trim(strip_tags($v));
            }
        }
        return $arr;
    } else {
        trigger_error('Input parameter has to be an array!');
    }
}

/**
 * проверка прав пользователя
 * @return boolean
 */
function checkAdmin() : bool
{
    if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'Administrator') {
        return true;
    } else {
        return false;
    }
}

/**
 * проверка прав пользователя
 * @return boolean
 */
function checkModer() : bool
{
    if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'Operator') {
        return true;
    } else {
        return false;
    }
}

/**
 * проверка авторизации
 */
function checkUser()
{
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['infoMsg'] = 'Вам необходимо авторизоваться или зарегистрироваться';
        header("Location: ".BASE_URL."login/");
    } else {
        if ($_SESSION['role'] == 'User') {
            header("Location: ".BASE_URL);
        }
        if ($_SESSION['role'] == 'Administrator') {
            header("Location: ". BASE_URL ."product/add");
        }
        if ($_SESSION['role'] == 'Moderator') {
            header("Location: ". BASE_URL ."orders/");
        }
    }
}

/**
 * pagination
 * формируем навигацию по страницам
 *
 * @param array $data - массив после выборки
 * @return array $result
 */
function pagination(array $data) : array
{
    $arrUri = defineCAP();
    # формируем начало строки адреса
    $urlPrefix = "/". $arrUri['controller'] ."/". $arrUri['action'] ."/";
    # формируем конец строки - $_GET
    $urlPostfix = '';
    if (count($arrUri['GET']) > 0) {
        foreach($arrUri['GET'] as $k=>$v) {
            $urlPostfix .= $k.'='.$v.'&';
        }
    }
    $urlPostfix = substr($urlPostfix,0,strlen($urlPostfix)-1); // удаляем последний &
    if (strlen($urlPostfix) > 1) {
        $urlPostfix = '?'.$urlPostfix; // добавляем в начало строки знак ?
    }
    # проверяем массивы;
    if (count($data) > 0) {
        # общее количество страниц
        $total = (count($data) > 1) ? intval((count($data)-1) / 6) + 1 : 0;
        $page = array_key_exists('page',$arrUri['params']) ? $arrUri['params']['page'] : 1;
        $arrPag = [];
        if ($page - 2 > 0) {
            $arrPag[0]['url'] = $urlPrefix.'page/'. intval($page-2) .'/'.$urlPostfix;
            $arrPag[0]['title'] = intval($page-2);
            $arrPag[0]['active'] = '';
        }
        if ($page - 1 > 0) {
            $arrPag[1]['url'] = $urlPrefix.'page/'. intval($page-1) .'/'.$urlPostfix;
            $arrPag[1]['title'] = intval($page-1);
            $arrPag[1]['active'] = '';
        }
        $cnt = count($arrPag);
        $arrPag[$cnt+1]['url'] = $urlPrefix.'page/'.intval($page).'/'.$urlPostfix;
        $arrPag[$cnt+1]['title'] = (int)$page;
        $arrPag[$cnt+1]['active'] = 'active';
        if ($page + 1 <= $total) {
            $arrPag[$cnt+2]['url'] = $urlPrefix.'page/'. intval($page+1) .'/'.$urlPostfix;
            $arrPag[$cnt+2]['title'] = intval($page+1);
            $arrPag[$cnt+2]['active'] = '';
        }
        if ($page + 2 <= $total) {
            $arrPag[$cnt+3]['url'] = $urlPrefix.'page/'. intval($page+2) .'/'.$urlPostfix;
            $arrPag[$cnt+3]['title'] = intval($page+2);
            $arrPag[$cnt+3]['active'] = '';
        }

        return $arrPag;
    } else {
        return [];
    }
}
