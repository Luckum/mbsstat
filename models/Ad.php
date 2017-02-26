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
        ];
    }

    public static function getAds()
    {
        $query = new Query;
        $query->select([
                'ad.*',
                'ad_public.*'
            ])
            ->from('ad')
            ->join('LEFT JOIN', 'ad_public', 'ad_public.ad_id = ad.id');
            
        $command = $query->createCommand();
        return $command->queryAll();
    }
}
