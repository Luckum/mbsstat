<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_price".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $old_price
 * @property string $new_price
 * @property string $old_price_purchase
 * @property string $new_price_purchase
 * @property integer $amount
 *
 * @property Product $product
 */
class ProductPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'old_price_purchase', 'new_price_purchase', 'amount'], 'required'],
            [['product_id', 'amount'], 'integer'],
            [['old_price', 'new_price', 'old_price_purchase', 'new_price_purchase'], 'number'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'old_price' => 'Old Price',
            'new_price' => 'New Price',
            'old_price_purchase' => 'Old Price Purchase',
            'new_price_purchase' => 'New Price Purchase',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
