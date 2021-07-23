<div class="card user-info-card height-100">
    <div class="card-header">
        Личные данные
    </div>
    <div class="card-body d-flex">
        <div class="d-flex align-items-center">
            <div class="d-flex flex-column skin-block">
                <div class="img-thumbnail p-2 skin-preview">
                    <img src="<?= $skin_preview_front ?>" />
                    <img src="<?= $skin_preview_back ?>" />
                </div>

                <div class="btn-group skin-control-skin">
                    <button type="button" class="btn btn-primary select-button">Выбрать скин</button>
                    <button type="button" class="btn btn-danger delete-button"><i class="fas fa-trash-alt"></i></button>
                </div>

                <div class="btn-group skin-control-cape" style="margin-top: 1px">
                    <button type="button" class="btn btn-primary select-button">Выбрать плащ</button>
                    <button type="button" class="btn btn-danger delete-button"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
        </div>

        <div class="info-block d-flex justify-content-center align-items-center flex-grow-1 ms-3">
            <table class="table">
                <tr>
                    <td>Логин:</td>
                    <td><?= $username ?></td>
                </tr>
                <tr>
                    <td>Реальная валюта:</td>
                    <td><?= skl($money, $sklrub) ?></td>
                </tr>
                <tr>
                    <td>Игровая валюта:</td>
                    <td><?= skl($iconomy, $skleco) ?></td>
                </tr>
                <tr>
                    <td>Группа: </td>
                    <td><?= $user_group['name'] ?></td>
                </tr>
                <tr>
                    <td>Срок:</td>
                    <td><?= $until ?></td>
                </tr>
                <tr>
                    <td>Кол-во покупок:</td>
                    <td><?= $buys ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>