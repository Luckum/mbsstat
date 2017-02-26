<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_detail".
 *
 * @property integer $id
 * @property integer $site_id
 * @property string $price_selling
 * @property string $comment
 *
 * @property Product[] $products
 * @property Site $site
 */
class ProductDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_id', 'price_selling'], 'required'],
            [['site_id'], 'integer'],
            [['price_selling'], 'number'],
            [['comment'], 'string'],
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
            'site_id' => 'Site ID',
            'price_selling' => 'Price Selling',
            'comment' => 'Comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['details_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }
}
