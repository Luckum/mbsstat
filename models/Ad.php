<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "ad".
 *
 * @property integer $id
 * @property string $creator
 * @property string $price
 * @property string $ad_type
 * @property integer $amount
 * @property string $paid_date
 * @property string $next_pay_date
 * @property string $period
 *
 * @property AdPublic[] $adPublics
 */
class Ad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creator', 'price', 'ad_type', 'amount'], 'required'],
            [['price'], 'number'],
            [['ad_type'], 'string'],
            [['amount'], 'integer'],
            [['paid_date', 'next_pay_date', 'period'], 'safe'],
            [['creator'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creator' => 'Creator',
            'price' => 'Price',
            'ad_type' => 'Ad Type',
            'amount' => 'Amount',
            'paid_date' => 'Paid Date',
            'next_pay_date' => 'Next Pay Date',
            'period' => 'Period',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdPublics()
    {
        return $this->hasMany(AdPublic::className(), ['ad_id' => 'id']);
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
    
    public static function getAdMonthes()
    {
        $query = new Query;
        $query->select([
            'period'
        ])
        ->from('ad')
        ->where('period IS NOT NULL')
        ->groupBy('period')
        ->orderBy('period DESC');
        
        $command = $query->createCommand();
        return $command->queryAll();
    }
}
