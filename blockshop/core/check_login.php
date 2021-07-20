<?php
//технически - это контроллер /auth
namespace route\auth;

use User;


//если отослана форма
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (preg_match('/^[a-zA-Z0-9_-]+$/', $username) && preg_match('/^[a-zA-Z0-9_-]+$/', $password)) {
        $user = User::whereFirst([
            'username' => $username,
            'hash' => sha1($password)
        ]);

        if (!empty($user)) {
            $_SESSION['shopname'] = $username;
            redirect_index();
        }
    } 
    
    _exit_with_template('auth1', ['error_message' => 'Неверный логин или пароль!']);
}


/*use DB;
use User;

function is_valid_field($field)
{
    return !empty($field) && strlen($field) < 20 && preg_match('/^[a-zA-Z0-9_-]+$/', $field);
}

function check_user_exists($username, $password)
{
    if (is_valid_field($username) && is_valid_field($password)) {
        $stmt = DB::prepare("SELECT * FROM users WHERE username = :username AND hash = :hash LIMIT 1");
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':hash', sha1($password));
        $stmt->execute();

        $user_data = $stmt->fetch();
        if (!empty($user_data)) {
            return $user_data;
        }
    }

    return false;
}

//todo: форму показывать на любой странице если нет сессии, но запрос всегда на /auth
//if route /auth
if ($_SERVER['REQUEST_URI'] == '/auth') {
    //если страница авторизации но юзер уже авторизован, редиректим
    if (!empty($_SESSION['shopname'])) {
        redirect_to_index();
    }

    //если отослана форма
    if (isset($_POST['username']) && isset($_POST['password'])) {
        
        $user_data = check_user_exists($_POST['username'], $_POST['password']);

        if (!$user_data) {
            _load_template('auth1', ['error_message' => 'Неверный логин или пароль!']);
        }

        $_SESSION['shopname'] = $user_data['username'];
        redirect_to_index();
    } else {
        _load_template('auth1');
        exit;
    }
}*/

/*function correct_user_fields($username, $password)
{
    if(empty($username) || empty($password)) {
        return false;
    }

    $regexp = '/^[a-zA-Z0-9_-]+$/';
    return preg_match($regexp, $username) && preg_match($regexp, $password);
}*/

/*
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
*/