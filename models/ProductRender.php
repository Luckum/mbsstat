<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_render".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $amount
 * @property string $render_price
 * @property string $render_date
 *
 * @property Product $product
 */
class ProductRender extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_render';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'amount', 'render_price', 'render_date'], 'required'],
            [['product_id', 'amount'], 'integer'],
            [['render_price'], 'number'],
            [['render_date'], 'safe'],
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
            'amount' => 'Amount',
            'render_price' => 'Render Price',
            'render_date' => 'Render Date',
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
