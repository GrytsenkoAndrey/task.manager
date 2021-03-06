<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 23.03.2020
 * Time: 19:38
 *
 * работа с таблицей товаров
 */

/**
 * выбрать все товары из таблицы - используется для формирования навигации
 *
 * @param resource $db
 * @return array $data
 */
function selectAllProd($db, string $category, array $get): array
{
    # category
    $cat = ($category == 'all') ? '%' : $category;
    # get
    $sort = " ";
    $new_sale = " ";
    $price = " ";
    if (count($get) > 0 && ( isset($get['new']) || isset($get['sale']) || isset($get['ord']) || isset($get['dir']) || isset($get['min']) || isset($get['max'])) ) {
        if (isset($get['new'])) {
            $new_sale = !empty($get['new']) ? "AND products.new_item = 1 " : '';
        }
        if (isset($get['sale'])) {
            $new_sale = !empty($get['sale']) ? "AND products.top_item = 1 " : '';
        }
        if (isset($get['new']) && isset($get['sale'])) {
            $new_sale = !empty($get['new']) && !empty($get['sale']) ? "AND products.new_item = 1 AND products.top_item = 1 " : '';
        }
        if (isset($get['ord']) && isset($get['dir'])) {
            $sort = !empty($get['ord']) && !empty($get['dir']) ? "ORDER BY products." . $get['ord'] . " " . $get['dir'] . " " : ' ';
        }
        if (isset($get['min'])) {
            $price = "AND products.price >= " . (float)$get['min'] . " ";
        }
        if (isset($get['max'])) {
            $price = "AND products.price <= " . (float)$get['max'] . " ";
        }
        if (isset($get['min']) && isset($get['max'])) {
            $price = "AND products.price >= " . (float)$get['min'] . " AND products.price <= " . (float)$get['max'] . " ";
        }
    } else {
        $new_sale = " ";
        $sort = " ";
        $price = " ";
    }

    $sql = "SELECT products.id, products.title, products.price, products.logo, products.new_item, c.description AS ctgry "
        ."FROM products "
        ."LEFT JOIN cat_prod AS cp ON products.id = cp.products_id "
        ."LEFT JOIN categories AS c ON c.id = cp.categories_id "
        ."WHERE c.category LIKE ? "
        .$new_sale
        .$price
        .$sort ;

    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $cat, \PDO::PARAM_STR);
    $stmt->execute();
    if ($row = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
        return $row;
    } else {
        return [];
    }
}

/**
 * считаем количество товаров в таблице
 *
 * @param array $data
 * @return int $qnt
 */
function selectProdQnt(array $data) : int
{
    return count($data);
}

/**
 * выбираем товары определенной категории
 *
 * @param resource $db
 * @param string $category
 * @param array $flags
 * @return array $data
 */
function selectProdByCategory($db, string $category, array $params, array $get, int $limit = 6): array
{
    # category
    $cat = ($category == 'all') ? '%' : $category;
    # get
    $sort = " ";
    $new_sale = " ";
    $price = " ";
    if (count($get) > 0 && ( isset($get['new']) || isset($get['sale']) || isset($get['ord']) || isset($get['dir']) || isset($get['min']) || isset($get['max'])) ) {
        if (isset($get['new'])) {
            $new_sale = !empty($get['new']) ? "AND products.new_item = 1 " : '';
        }
        if (isset($get['sale'])) {
            $new_sale = !empty($get['sale']) ? "AND products.top_item = 1 " : '';
        }
        if (isset($get['new']) && isset($get['sale'])) {
            $new_sale = !empty($get['new']) && !empty($get['sale']) ? "AND products.new_item = 1 AND products.top_item = 1 " : '';
        }
        if (isset($get['ord']) && isset($get['dir'])) {
            $sort = !empty($get['ord']) && !empty($get['dir']) ? "ORDER BY products." . $get['ord'] . " " . $get['dir'] . " " : ' ';
        }
        if (isset($get['min'])) {
            $price = "AND products.price >= " . (float)$get['min'] . " ";
        }
        if (isset($get['max'])) {
            $price = "AND products.price <= " . (float)$get['max'] . " ";
        }
        if (isset($get['min']) && isset($get['max'])) {
            $price = "AND products.price >= " . (float)$get['min'] . " AND products.price <= " . (float)$get['max'] . " ";
        }
    } else {
        $new_sale = " ";
        $sort = " ";
        $price = " ";
    }
    # limit
    $lim = (isset($params['page'])) ? ((int)$params['page'] - 1) * $limit : 0;

    $sql = "SELECT products.id, products.title, products.price, products.logo, products.new_item, c.description AS ctgry "
        ."FROM products "
        ."LEFT JOIN cat_prod AS cp ON products.id = cp.products_id "
        ."LEFT JOIN categories AS c ON c.id = cp.categories_id "
        ."WHERE c.category LIKE ? "
        .$new_sale
        .$price
        .$sort
        ."LIMIT ?, {$limit}";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $cat, \PDO::PARAM_STR);
    $stmt->bindValue(2, $lim, \PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
        return $row;
    } else {
        return [];
    }
}

/**
 * @param array $rsProduct
 * @return array $data
 */
function formatPriceInData(array $rsProduct): array
{
    if (count($rsProduct) > 0) {
        $i = 0;
        foreach ($rsProduct as $item) {
            foreach ($item as $k => $v) {
                if ($k == 'price') {
                    $rsProducts[$i][$k] = number_format($v, 0, '', ' ');
                } else {
                    $rsProducts[$i][$k] = $v;
                }
            }
            $i++;
        }
        return $rsProducts;
    } else {
        return [];
    }
}