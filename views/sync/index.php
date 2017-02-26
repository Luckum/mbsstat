<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>


<h3 class="page-header">Синхронизация</h3>
<br />

<div>
    <?php foreach ($sites as $row): ?>
        <p>Сайт <b><?= $row['name']; ?></b>. Время последней синхронизации: <b>Сегодня в <?= date('H:i:s', strtotime($row['last_sync_date'])); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;
        <a name="sync_now_<?= $row['id']; ?>" href="<?= Url::to(['sync/syncnow/' . $row['id']]); ?>" type="button" class="btn btn-primary btn-xs">Синхронизировать</a></p>
    <?php endforeach; ?>
</div>