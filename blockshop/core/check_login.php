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