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
 *
 * @property AdPublic $adPublic
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
            [['creator'], 'url'],
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
        ];
    }
    
    public static function getAdTotal()
    {
        $query = new Query();
        $query->select(['SUM(price) AS price'])
            ->from(self::tableName());
        
        $command = $query->createCommand();
        $ad = $command->queryOne();
        return $ad['price'];
    }
}
