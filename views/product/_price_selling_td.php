<span><?= number_format($product['price_selling'], 2, '.', ' '); ?></span>
<input type="hidden" name="product_id_td" value="<?= $product['inner_product_id']; ?>">
<input type="hidden" name="site_id_td" value="<?= $product['site_id']; ?>">
<input type="hidden" name="site_name_td" value="<?= $sitename; ?>">