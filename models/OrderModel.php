<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 29.03.2020
 * Time: 19:19
 */

/**
 * добавление товара в заказы
 *
 * @param resource $db
 * @param array $data
 */
function addProdToOder($db, array $data)
{
    $sql = "INSERT INTO orders "
        ."VALUES (null, :pid, :payst, :payam, :dcr, :n, :sn, :thn, :phone, :e, :del, :payt, :com)";
    $stmt = $db->prepare($sql);
    $stmt->execute([':pid' => $data['id'],
                    ':payst' => 0,
                    ':payam' => $data['price'],
                    ':dcr' => date('Y-m-d H:i:s'),
                    ':n' => $data['name'],
                    ':sn' => $data['surname'],
                    ':thn' => $data['thirdname'],
                    ':phone' => $data['phone'],
                    ':e' => $data['email'],
                    ':del' => (!empty($data['dev-yes'])) ? 1 : 0,
                    ':payt' => (!empty($data['cash'])) ? 1 : 0,
                    ':com' => $data['comment'],
    ]);
    $id = $db->lastInsertId();
}

/**
 * выбираем заказы
 *
 * @param resource $db
 * @param array $params
 */
function selectOrders($db, array $params) : array
{
    # limit
    $lim = (isset($params['page'])) ? ((int)$params['page'] - 1) * 15 : 0;

    $sql = "SELECT id, payment_status, payment_amount, date_created, name, sname, fname, phone, email, delivery, paytype, comments "
        ."FROM orders "
        ."ORDER BY date_created DESC, payment_status ASC "
        ."LIMIT ?, 15";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(1, $lim, \PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
        return $row;
    } else {
        return [];
    }
}

/**
 * изменяем статус заказа на Выполнен
 *
 * @param resource $db
 * @param array $param
 */
function updOrderStatus($db, array $param)
{
    $sql = "UPDATE orders SET payment_status = :status WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':status' => $param['status'],
                    ':id' => $param['id'],
    ]);
}

/**
 * количество всех заказов
 *
 * @param resource $db
 * @return array $data
 */
function getAllOrders($db) : array
{
    $sql = "SELECT id FROM orders";
    $stmt = $db->query($sql);
    if ($row = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
        return $row;
    } else {
        return [];
    }
}