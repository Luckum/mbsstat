<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sync_setting".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $site_id
 *
 * @property Product $product
 * @property Site $site
 */
class SyncSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sync_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'site_id'], 'required'],
            [['product_id', 'site_id'], 'integer'],
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
            'product_id' => 'Product ID',
            'site_id' => 'Site ID',
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
    
    public static function saveNewData($products, $siteId)
    {
        SyncSetting::deleteAll(['site_id' => $siteId]);
        foreach ($products as $p_id) {
            $sync = new SyncSetting();
            $sync->site_id = $siteId;
            $sync->product_id = $p_id;
            $sync->save();
        }
    }
    
    public static function isInSync($product_id, $site_id)
    {
        return SyncSetting::find()->where(['product_id' => $product_id, 'site_id' => $site_id])->exists();
    }
    
    public static function getProductsBySite($site_id)
    {
        $products = [];
        $sync_products = self::find()->with('product')->where(['site_id' => $site_id])->all();
        if ($sync_products) {
            foreach ($sync_products as $row) {
                $products[] = $row->product;
            }
        }
        return $products;
    }
    
    public static function getProductsUnique()
    {
        $products = [];
        $sync_products = self::find()->with('product')->groupBy('product_id')->all();
        if ($sync_products) {
            foreach ($sync_products as $row) {
                $products[] = $row->product;
            }
        }
        return $products;
    }
}
