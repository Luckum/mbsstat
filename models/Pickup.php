<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "pickup".
 *
 * @property integer $id
 * @property string $amount
 * @property string $pickup_date
 */
class Pickup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pickup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'pickup_date'], 'required'],
            [['amount'], 'number'],
            [['pickup_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'pickup_date' => 'Pickup Date',
        ];
    }
    
    public static function getPickupsByMonth($month)
    {
        return self::findAll([
            'pickup_date' => $month,
        ]);
    }
    
    public static function getTotalByMonth($month)
    {
        $query = new Query();
        $query->select(['SUM(amount) AS amount'])
            ->from(self::tableName())
            ->where(['pickup_date' => $month]);
        
        $command = $query->createCommand();
        $pickup = $command->queryOne();
        return $pickup['amount'];
    }
}
