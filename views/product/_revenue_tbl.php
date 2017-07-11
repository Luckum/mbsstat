<table class="table table-striped table-bordered table-hover">
    <?php foreach ($incomes as $income): ?>
        <tr>
            <td><?= $income->type; ?></td>
            <td><?= number_format($income->amount, 2, '.', ' '); ?></td>
        </tr>
    <?php endforeach; ?>
</table>