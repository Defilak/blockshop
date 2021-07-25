
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