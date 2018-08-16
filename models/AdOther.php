<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "ad_other".
 *
 * @property integer $id
 * @property integer $ad_category_id
 * @property string $name
 * @property string $price
 * @property string $paid_date
 * @property string $next_pay_date
 * @property string $period
 *
 * @property AdCategory $adCategory
 */
class AdOther extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_other';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ad_category_id', 'name', 'price', 'paid_date', 'next_pay_date', 'period'], 'required'],
            [['ad_category_id'], 'integer'],
            [['price'], 'number'],
            [['paid_date', 'next_pay_date', 'period'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['ad_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdCategory::className(), 'targetAttribute' => ['ad_category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_category_id' => 'Ad Category ID',
            'name' => 'Name',
            'price' => 'Price',
            'paid_date' => 'Paid Date',
            'next_pay_date' => 'Next Pay Date',
            'period' => 'Period',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdCategory()
    {
        return $this->hasOne(AdCategory::className(), ['id' => 'ad_category_id']);
    }
    
    public static function getAdMonthes()
    {
        $query = new Query;
        $query->select([
            'period'
        ])
        ->from('ad_other')
        ->where('period IS NOT NULL')
        ->groupBy('period')
        ->orderBy('period DESC');
        
        $command = $query->createCommand();
        return $command->queryAll();
    }
    public static function getAdTotal($month)
    {
        $query = new Query();
        $query->select(['SUM(price) AS price'])
            ->from(self::tableName())
            ->where(['period' => $month]);
        
        $command = $query->createCommand();
        $ad = $command->queryOne();
        return $ad['price'];
    }
    
    public static function getAds($cat_id, $month = '')
    {
        if (!empty($month)) {
            return AdOther::find()->where(['ad_category_id' => $cat_id, 'period' => $month])->all();
        } else {
            return AdOther::find()->where(['ad_category_id' => $cat_id])->groupBy('name')->all();
        }
    }
}
