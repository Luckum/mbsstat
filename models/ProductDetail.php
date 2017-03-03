<?php

namespace app\models;

use Yii;
use yii\db\Query;

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
            [['site_id', 'price_selling', 'inner_product_id'], 'required'],
            [['site_id', 'inner_product_id'], 'integer'],
            [['price_selling'], 'number'],
            [['comment'], 'string'],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['site_id' => 'id']],
            [['inner_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['inner_product_id' => 'id']],
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
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }
    
    public static function getDetailsBySite($site_id, $product_id)
    {
        return self::findOne([
            'site_id' => $site_id,
            'inner_product_id' => $product_id
        ]);
    }
}
