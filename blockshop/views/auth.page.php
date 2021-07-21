<?php
if (!defined('BLOCKSHOP')) {
    die;
}

require_once dirname(__FILE__) . '/../config.php';
require_once 'header.php';

if (isset($_POST['login']) && isset($_POST['password'])) {
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
    header('Location: http://localhost:8000/');
}

?>

<div class="container">
    <div class="row">
        <div class="col-3 mx-auto d-flex align-items-center">
            <div class="card w-100">
                <h5 class="card-header text-center">Вход</h5>
                <div class="card-body">
                    <form method="POST" action="/auth">

                        <div>
                            <label class="form-label" for="login">Логин</label>
                            <input class="form-control" name="login" />
                        </div>

                        <div class="my-3">
                            <label class="form-label" for="password">Пароль</label>
                            <input class="form-control" type="password" name="password" />
                        </div>

                        <div class="text-center">
                            <input class="btn btn-primary" type="submit" value="Вход">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
require_once 'footer.php';
?>