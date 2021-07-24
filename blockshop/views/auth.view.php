<div class="d-flex justify-content-center align-items-center" style="height:100%">

    <div>
        <form class="d-flex flex-column" method="post" action="/auth">
            <label for="username">Логин</label>
            <input type="text" name="username">

            <label for="password">Пароль</label>
            <input type="password" name="password">

            <input class="button mt-2" type="submit" value="Войти">

            <? if (isset($error_message)) { ?>
                <div class="alert alert-danger mt-3">
                    <?= $error_message ?>
                </div>
            <? } ?>
        </form>
    </div>

</div>