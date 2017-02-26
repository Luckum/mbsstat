<?php

namespace app\models;

use Yii;
use yii\db\Query;
use app\models\Outlay;

/**
 * This is the model class for table "income".
 *
 * @property integer $id
 * @property string $type
 * @property integer $amount
 * @property string $income_date
 */
class Income extends \yii\db\ActiveRecord
{
    const GROUP_REVENUE = "ОБЩАЯ ВЫРУЧКА, руб.";
    const GROUP_REVENUE_CLEAR = "ОБЩАЯ ЧИСТАЯ, руб.";
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'income';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'amount', 'income_date'], 'required'],
            [['amount'], 'double'],
            [['income_date'], 'safe'],
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
            'income_date' => 'Income Date',
        ];
    }
    
    public static function setIncomes($month, $t_rev, $t_rev_clear)
    {
        $income = self::findOne([
            'type' => self::GROUP_REVENUE,
            'income_date' => $month,
        ]);
        if ($income) {
            if ($t_rev != $income->amount) {
                $income->amount = $t_rev;
                $income->amount_init = $t_rev;
                $income->save();
            }
        } else {
            $income = new Income();
            $income->type = self::GROUP_REVENUE;
            $income->income_date = $month;
            $income->amount = $t_rev;
            $income->amount_init = $t_rev;
            $income->save();
        }
        
        $income_cl = self::findOne([
            'type' => self::GROUP_REVENUE_CLEAR,
            'income_date' => $month,
        ]);
        if ($income_cl) {
            if ($t_rev != $income_cl->amount) {
                $income_cl->amount = $t_rev_clear;
                $income_cl->amount_init = $t_rev_clear;
                $income_cl->save();
            }
        } else {
            $income_cl = new Income();
            $income_cl->type = self::GROUP_REVENUE_CLEAR;
            $income_cl->income_date = $month;
            $income_cl->amount = $t_rev_clear;
            $income_cl->amount_init = $t_rev_clear;
            $income_cl->save();
        }
    }
    
    public static function recalcIncomes($month)
    {
        $income = self::findOne([
            'type' => self::GROUP_REVENUE_CLEAR,
            'income_date' => $month,
        ]);
        
        $query = new Query();
        $query->select(['SUM(amount) AS amount'])
            ->from(Outlay::tableName())
            ->where(['outlay_date' => $month]);
        
        $command = $query->createCommand();
        $outlay = $command->queryOne();
        
        $income->amount = $income->amount_init - $outlay['amount'];
        $income->save();
    }
    
    public static function getIncomesByMonth($month)
    {
        return self::findAll([
            'income_date' => $month,
        ]);
    }
    
    public static function getIncomeByMonth($type, $month)
    {
        return self::findOne([
            'type' => $type,
            'income_date' => $month,
        ]);
    }
}
