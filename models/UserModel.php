<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 24.03.2020
 * Time: 19:44
 *
 * работа с таблицей товаров
 */

/**
 * страница авторизации пользователя
 *
 * @param resource $db
 * @param array $post - for data
 */
function login($db, array $post)
{
    // check form data
    $login = strip_tags(trim($post['email'])) ?? '';
    $pass = isset($post['password']) ? strip_tags(trim($post['password'])) : '';

    $sql = "SELECT u.id, u.second_name, u.name, u.login, u.pass, g.name AS role "
        ."FROM users AS u "
        ."LEFT JOIN users_groups AS ug ON ug.user_id = u.id "
        ."LEFT JOIN groups AS g ON g.id = ug.group_id "
        ."WHERE u.login = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $login]);
    $dat = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    if (count($dat) >= 1) {
        if (password_verify($pass, $dat[0]['pass']) == true) {
            $sql = "UPDATE users SET active=1, last_visit=:act_time WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':act_time' => date("Y-m-d H:i", time()),
                ':id' => $dat[0]['id'],
            ]);
            $_SESSION['user_id'] = $dat[0]['id'];
            $_SESSION['act_time'] = date("Y-m-d G:i:s", time());
            $_SESSION['user_name'] = $dat[0]['second_name'].' '.$dat[0]['name'];
            $_SESSION['login'] = $dat[0]['login'];
            $_SESSION['role'] = $dat[0]['role'];
            // go to messages
            header("Location: /");
        } else {
            $_SESSION['infoMsg'] = '<div class="alert alert-danger">Ошибка ввода: нет такого пользователя или неправильный пароль!</div>';
            header("Location: ".BASE_URL."user/login");
        }

    } else {
        $_SESSION['infoMsg'] = '<div class="alert alert-danger">Ошибка ввода: нет такого пользователя или неправильный пароль!</div>';
        header("Location: ".BASE_URL."user/login");
    }
}

/**
 * функция регистрации нового пользователя
 * @param resource $db - DB connection
 */
function registration($db, array $post)
{
    $data = functions\clearData($post);
    # выставляем группу пользователя - обычный (можно было добавить поле в форму регистрации, но не стал)
    $data['groups'] = 3;
    $pdo = nic\getConn();
    $sql = "SELECT u.login FROM users AS u WHERE u.login = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $data['login']]);
    $dat = $stmt->fetchAll();
    if (count($dat) < 1) {
        // database
        // insert into users
        $sql = "INSERT INTO users VALUES (null, :active, :last_visit, :date_created, :second_name, :name, :last_name, :login, :pass, :phone, :notification)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':active' => 1,
            ':last_visit' => date("Y-m-d H-i-s", time()),
            ':date_created' => date("Y-m-d H-i-s", time()),
            ':second_name' => $data['sname'],
            ':name' => $data['name'],
            ':last_name' => $data['lname'],
            ':login' => $data['login'],
            ':pass' => password_hash($data['pass'], PASSWORD_DEFAULT), //md5($data['pass'].SALT),
            ':phone' => $data['phone'],
            ':notification' => 0,
        ]);
        $id = $pdo->lastInsertId();
        // insert into user_group
        $sql = "INSERT INTO users_groups VALUES (:user_id, :group_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $id,
            ':group_id' => $data['groups'],
        ]);
        // get data
        $sql = "SELECT u.id, u.notification, g.name AS role "
            . "FROM users AS u "
            . "LEFT JOIN users_groups AS ug ON ug.user_id = u.id "
            . "LEFT JOIN groups AS g ON g.id = ug.group_id "
            . "WHERE u.login = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $data['login']]);
        $dat = $stmt->fetch();
        // session
        $_SESSION['user_id'] = $dat['id'];
        $_SESSION['act_time'] = date("Y-m-d H:i", time());
        $_SESSION['user_name'] = $data['sname'].' '.$data['name'];
        $_SESSION['login'] = $data['login'];
        $_SESSION['role'] = $dat['role'];
        header("Location: ".BASE_URL);
    } else {
        $_SESSION['infoMsg'] = '<p class="bg-danger">Ошибка: такой пользователь уже существует!</p>';
        header("Location: ".BASE_URL."register/");
    }

}

/**
 *
 */
function logout($db)
{
    if (isset($_SESSION['user_id'])) {
        $sql = "UPDATE users SET active=0 WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $_SESSION['user_id']]);
    }

    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600);
}