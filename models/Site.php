<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "site".
 *
 * @property integer $id
 * @property string $name
 * @property date $last_sync_date
 *
 * @property ProductDetail[] $productDetails
 */
class Site extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductDetails()
    {
        return $this->hasMany(ProductDetail::className(), ['site_id' => 'id']);
    }
    
    public static function saveSyncDate($siteId)
    {
        $site = self::findOne($siteId);
        $site->last_sync_date = date("Y-m-d H:i:s");
        $site->save();
    }
}
