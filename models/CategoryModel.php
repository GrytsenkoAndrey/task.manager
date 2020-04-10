<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 25.03.2020
 * Time: 11:36
 */

/**
 * работа с таблицей категорий
 */

/**
 * выбираем категории
 *
 * @param resource $db
 * @return array $data
 */
function getAllCategories($db) : array
{
    $sql = "SELECT id, category, description FROM categories ORDER BY id ASC";
    $stmt = $db->query($sql);
    if ($row = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
        return $row;
    } else {
        return [];
    }
}

/**
 * выбираем категорию по названию
 *
 * @param resource $db
 * @param string $title
 * @return int $id
 */
function selCatByTitle($db, string $title) : int
{
    $sql = "SELECT id FROM categories WHERE category = :cat";
    $stmt = $db->prepare($sql);
    $stmt->execute([':cat' => $title, ]);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $row['id'];
}

/**
 * добавляем данные в таблицу cat_prod
 *
 * @param resource $db
 * @param int $prod_id
 * @param int $cat_id
 */
function addToCatProd($db, int $prod_id, int $cat_id)
{
    $sql = "INSERT INTO cat_prod VALUES(:prod_id, :cat_id)";
    $stmt = $db->prepare($sql);
    $stmt->execute([':prod_id' => $prod_id,
                    ':cat_id' => $cat_id,
    ]);
}

/**
 * обновляем данные в таблице cat_prod
 *
 * @param resource $db
 * @param int $prod_id
 * @param int $cat_id
 */
function updCatProd($db, int $prod_id, int $cat_id)
{
    $sql = "UPDATE cat_prod SET categories_id = :cat_id WHERE products_id = :prod_id ";
    $stmt = $db->prepare($sql);
    $stmt->execute([':cat_id' => $cat_id,
                    ':prod_id' => $prod_id,
    ]);

}