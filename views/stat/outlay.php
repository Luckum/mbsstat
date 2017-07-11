<?php if (!empty($error)): ?><p><?= $error; ?></p><?php endif; ?>
<div id="revenue-tbl">
    <table class="table table-striped table-bordered table-hover">
        <?php foreach ($incomes as $income): ?>
            <tr>
                <td><?= $income->type; ?></td>
                <td><?= number_format($income->amount, 2, '.', ' '); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<p><b>ЗАТРАТЫ</b></p>

<table class="table table-striped table-bordered table-hover">
    <?php foreach ($outlays as $outlay): ?>
        <tr>
            <td><span class="o-type"><?= $outlay->type; ?></span></td>
            <td><span class="o-amount"><?= number_format($outlay->amount, 2, '.', ' '); ?></span></td>
            <td>
                <a href="javascript:void(0);" title="Редактировать" data-toggle="modal" data-target="#outlay_modal_edit" onclick="showOutlayModal(this);"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="javascript:void(0)" title="Удалить" onclick="deleteOutlay(this);"><span class="glyphicon glyphicon-trash"></span></a>
                <input type="hidden" class="o-id" value="<?= $outlay->id; ?>">
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<p><b>ЗАБРАНО</b></p>
                
<table class="table table-striped table-bordered table-hover">
    <?php foreach ($pickups as $pickup): ?>
        <tr>
            <td><?= $pickup->pickup_datetime; ?></td>
            <td><?= number_format($pickup->amount, 2, '.', ' '); ?> руб.</td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td><b>ВСЕГО ЗАБРАНО:</b></td>
        <td><b><?= number_format($totalPickup, 2, '.', ' '); ?> руб.</b></td>
    </tr>
</table>
<p id="cashbox-container"><b>В КАССЕ: <?= number_format($cashbox, 2, '.', ' '); ?> руб.</b></p>

<p><b>ОСТАТОК</b></p>
<table class="table table-striped table-bordered table-hover">
    <tr>
        <td>ЗАКУПОЧНАЯ</td>
        <td id="residue-purchase-td"><?= number_format($residuePurchase, 2, '.', ' '); ?> руб.</td>
    </tr>
    <tr>
        <td>ДОЛГОВАЯ</td>
        <td><?= number_format($residueDebt, 2, '.', ' '); ?> руб.</td>
    </tr>
    <tr>
        <td><b>ОБЩАЯ</b></td>
        <td id="residue-total-td"><b><?= number_format($residuePurchase + $residueDebt + $cashbox, 2, '.', ' '); ?> руб.</b></td>
    </tr>
</table>