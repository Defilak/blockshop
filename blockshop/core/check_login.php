<?php
if (!defined('BLOCKSHOP')) {
    die;
}


if ($_SERVER['REQUEST_URI'] == '/auth' || (isset($_POST['login']) && isset($_POST['password']))) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $is_valid = false;

    if (preg_match('/^[a-zA-Z0-9_-]+$/', $login) && preg_match('/^[a-zA-Z0-9_-]+$/', $password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND hash = :hash");
        $stmt->bindValue(':username', $login);
        $stmt->bindValue(':hash', sha1($password));
        $stmt->execute();

        $user = $stmt->fetch();
        if (!empty($user)) {
            $is_valid = true;
        }
    }

    if (!$is_valid) {
        die('Неверный логин или пароль!');
    }

    $_SESSION['shopname'] = $login;
    header('Location: /');
}

// Показываю страницу логина если нет сессии.
if (empty($_SESSION['shopname'])) {
    _include_page('auth.page.php');
    die;
}