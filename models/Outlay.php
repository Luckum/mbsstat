<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "outlay".
 *
 * @property integer $id
 * @property string $type
 * @property integer $amount
 * @property string $outlay_date
 */
class Outlay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'outlay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'amount', 'outlay_date'], 'required'],
            [['amount'], 'double'],
            [['outlay_date'], 'safe'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'outlay_date' => 'Outlay Date',
        ];
    }
    
    public static function getOutlaysByMonth($month)
    {
        return self::findAll([
            'outlay_date' => $month,
        ]);
    }
    
    public static function getTotalByMonth($month)
    {
        $query = new Query();
        $query->select(['SUM(amount) AS amount'])
            ->from(self::tableName())
            ->where(['outlay_date' => $month]);
        
        $command = $query->createCommand();
        $outlay = $command->queryOne();
        return $outlay['amount'];
    }
}
