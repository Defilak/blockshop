<!DOCTYPE html>
<html>

<head>
    <title>Личный кабинет</title>

    <meta charset=utf-8>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/blockshop/css/grid/simple-grid.css" />

    <link href="./favicon.ico" rel="icon" type="image/x-icon" />

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php

                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);

                require_once 'blockshop/index.php';

                ?>
            </div>
        </div>
    </div>

</body>

</html>