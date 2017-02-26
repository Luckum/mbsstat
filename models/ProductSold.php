<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_sold".
 *
 * @property integer $id
 * @property string $sale_date
 * @property integer $product_id
 * @property integer $site_id
 * @property integer $amount
 *
 * @property Product $product
 * @property Site $site
 */
class ProductSold extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_sold';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_date', 'product_id', 'site_id', 'amount'], 'required'],
            [['sale_date'], 'safe'],
            [['product_id', 'site_id', 'amount'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['site_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_date' => 'Sale Date',
            'product_id' => 'Product ID',
            'site_id' => 'Site ID',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }
    
    public static function getTotalByProduct($id, $month)
    {
        return self::findOne([
            'product_id' => $id,
            'sale_date' => $month
        ]);
    }
}
