<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_details}}".
 *
 * @property string $item_id
 * @property string $order_id
 * @property string $product_id
 * @property string $product_code
 * @property string $price
 * @property integer $amount
 * @property resource $extra
 * @property string $weight
 */
class OrderDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_details}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_mbs');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'order_id', 'extra'], 'required'],
            [['item_id', 'order_id', 'product_id', 'amount'], 'integer'],
            [['price', 'weight'], 'number'],
            [['extra'], 'string'],
            [['product_code'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'price' => 'Price',
            'amount' => 'Amount',
            'extra' => 'Extra',
            'weight' => 'Weight',
        ];
    }
}
