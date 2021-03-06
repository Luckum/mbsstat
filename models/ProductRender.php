<?php

namespace app\models;

use Yii;
use yii\db\Query;

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
    
    public static function getRenderTotal($product_id, $month)
    {
        $query = new Query();
        $query->select(['SUM(amount) AS amount', 'SUM(render_price) AS render_price'])
            ->from(self::tableName())
            ->where(['product_id' => $product_id, 'render_date' => $month]);
        
        $command = $query->createCommand();
        return $command->queryOne();
    }
    
    public static function getSumRenderByProduct($product_id)
    {
        return self::find()->where([
            'product_id' => $product_id,
        ])->sum('amount');
    }
}
