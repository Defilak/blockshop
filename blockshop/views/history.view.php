<? if (count($result) == 0) { ?>
    <?= responses\infly('История пуста!'); ?>
<? } else { ?>
    <?= responses\infly('Здесь отображаются все совершенные вами действия в магазине за ближайшие 10 суток.'); ?>
    <div class="row my-4">
        <div class="col-12 col-lg-10 mx-auto">

            <table class="table history-table">
                <? foreach ($result as $row) { ?>
                    <tr>
                        <td><img src="<?= blockshop_public("assets/img/icons/{$row['img']}") ?>"></td>
                        <td><b><?= $row['name'] ?></b></td>
                        <td><b><?= $row['info'] ?></b></td>
                        <td class="date"><b><?= $row['date'] ?></b></td>
                    </tr>
                <? } ?>
            </table>

        </div>
    </div>
<? } ?>