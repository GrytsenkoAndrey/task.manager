<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 27.03.2020
 * Time: 11:27
 */
require_once 'CategoryModel.php';
/**
 * добавление товара в базу данных
 *
 * @param resource $db
 * @param array $data
 */
function addProd($db, array $data)
{
    $data = functions\clearData($data);
    $sql = "INSERT INTO products "
        . "VALUES (NULL, :title, :description, :logo, :price, :quantity, :new_item, :top_item)";
    $stmt = $db->prepare($sql);
    $stmt->execute([':title' => $data['product-name'],
                    ':description' => $data['product-name'],
                    ':logo' => $data['product-photo'],
                    'price' => $data['product-price'],
                    'quantity' => $data['product-qnt'],
                    ':new_item' => (isset($data['new'])) ? 1 : 0,
                    ':top_item' => (isset($data['sale'])) ? 1 : 0,
    ]);
    $prod_id = $db->lastInsertId();
    $cat_id = selCatByTitle($db, $data['category']);
    addToCatProd($db, $prod_id, $cat_id);
}

/**
 * выбираем данные о товаре для редактирования
 *
 * @param resource $db
 * @param array $params
 * @return array $data
 */
function selectProductById($db, array $params) : array
{
    $sql = "SELECT prod.id, prod.title, prod.price, prod.logo, prod.quantity, prod.new_item, prod.top_item, c.category AS ctgry "
        ."FROM products AS prod "
        ."LEFT JOIN cat_prod AS cp ON prod.id = cp.products_id "
        ."LEFT JOIN categories AS c ON c.id = cp.categories_id "
        ."WHERE prod.id = ? ";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $params['id'], \PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $row['new_item'] = ($row['new_item'] == 1) ? " checked='checked' " : '';
        $row['top_item'] = ($row['top_item'] == 1) ? " checked='checked' " : '';
        return $row;
    } else {
        return [-1];
    }
}

/**
 * обновляем данные о продукте
 *
 * @param resource $db
 * @param array $params
 */
function updateProduct($db, array $data)
{
    $sql = "UPDATE products "
        ."SET title = :title, logo = :logo, price = :price, quantity = :quantity, new_item = :new_item, top_item = :top_item "
        ."WHERE id = :id ";
    $stmt = $db->prepare($sql);
    $stmt->execute([':title' => $data['product-name'],
                    ':logo' => (empty(trim($data['product-photo']))) ? $_SESSION['pr-logo'] : $data['product-photo'],
                    ':price' => $data['product-price'],
                    ':quantity' => $data['product-qnt'],
                    ':new_item' => ($data['new'] == 'true') ? 1 : 0,
                    ':top_item' => ($data['sale'] == 'true') ? 1 : 0,
                    ':id' => (int)$data['id'],
    ]);
    $cat_id = selCatByTitle($db, $data['category']);
    updCatProd($db, $data['id'], $cat_id);
    #$_SESSION['pr-logo'] = '';
}

/**
 * удалить товар из каталога
 *
 * @param resource $db
 * @param int $id
 */
function delProductById($db, int $id)
{
    $sql ="DELETE FROM products WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $id]);
}