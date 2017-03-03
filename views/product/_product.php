<option value="0" disabled selected>- Выберите товар -</option>
<?php foreach ($products as $product): ?>
    <option value="<?= $product['id']; ?>"><?= $product['product_name']; ?></option>
<?php endforeach; ?>