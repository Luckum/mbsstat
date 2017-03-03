<div class="form-group">
    <label class="col-sm-2 control-label">Сейчас на складе</label>
    <div class="col-sm-4">
        <label class="control-label"><?= $details['amount_supplied']; ?> шт.</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">По закупочной цене</label>
    <div class="col-sm-4">
        <label class="control-label"><?= number_format($details['price_purchase'], 2, '.', ' '); ?> руб.</label>
    </div>
</div>

<div class="form-group">
    <label for="amount" class="col-sm-2 control-label">Принять на склад</label>
    <div class="col-sm-4  has-feedback">
        <input type="text" class="form-control" id="amount" name="amount">
        <span class="form-control-feedback">шт.</span>
    </div>
</div>

<div class="form-group">
    <label for="old_price" class="col-sm-2 control-label">По старой цене</label>
    <div class="col-sm-4">
        <input type="checkbox" id="old_price" name="old_price" value="Y" checked onclick="showPrice();">
    </div>
</div>

<div class="form-group" id="new_price" style="display: none;">
    <label for="price" class="col-sm-2 control-label">По закупочной цене</label>
    <div class="col-sm-4  has-feedback">
        <input type="text" class="form-control" id="price" name="price">
        <span class="form-control-feedback">руб.</span>
    </div>
</div>

<div class="form-group" id="sold_old" style="display: none;">
    <label for="sold_old_price" class="col-sm-2 control-label">Продать остаток по старой цене</label>
    <div class="col-sm-4">
        <input type="checkbox" id="sold_old_price" name="sold_old_price" value="Y">
        <span class="help-block">Если вы хотите, чтобы остаток товаров был продан по старой цене, отметьте это поле. Новую цену продажи вы сможете указать на странице отчета</span>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary" name="accept">Принять</button>
    </div>
</div>