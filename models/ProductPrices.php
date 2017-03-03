<?php

namespace app\models;

use Yii;
use app\models\Products;

/**
 * This is the model class for table "{{%product_prices}}".
 *
 * @property string $product_id
 * @property string $price
 * @property string $percentage_discount
 * @property integer $lower_limit
 * @property string $usergroup_id
 */
class ProductPrices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_prices}}';
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
            [['product_id', 'lower_limit', 'usergroup_id'], 'required'],
            [['product_id', 'percentage_discount', 'lower_limit', 'usergroup_id'], 'integer'],
            [['price'], 'number'],
            [['product_id', 'usergroup_id', 'lower_limit'], 'unique', 'targetAttribute' => ['product_id', 'usergroup_id', 'lower_limit'], 'message' => 'The combination of Product ID, Lower Limit and Usergroup ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'price' => 'Price',
            'percentage_discount' => 'Percentage Discount',
            'lower_limit' => 'Lower Limit',
            'usergroup_id' => 'Usergroup ID',
        ];
    }
    
    public static function getProductPriceByCode($code)
    {
        $sql = 'SELECT cpp.price, cpp.product_id FROM ' . self::tableName() . ' 
                LEFT JOIN ' . Products::tableName() . ' cp ON cpp.product_id = cp.product_id
                WHERE product_code = :product_code';
        return self::findBySql($sql, ['product_code' => $code])->one();
    }
}