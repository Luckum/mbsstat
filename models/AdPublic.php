<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "ad_public".
 *
 * @property integer $id
 * @property string $ad_group
 * @property integer $ad_id
 *
 * @property Ad $ad
 */
class AdPublic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_public';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ad_group', 'ad_id'], 'required'],
            [['ad_id'], 'integer'],
            [['ad_group'], 'url'],
            [['ad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ad::className(), 'targetAttribute' => ['ad_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_group' => 'Ad Group',
            'ad_id' => 'Ad ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ad::className(), ['id' => 'ad_id']);
    }
    
    public static function getAds()
    {
        $query = new Query;
        $query->select([
                'ad.*',
                'ad_public.*'
            ])
            ->from(self::tableName())
            ->join('LEFT JOIN', 'ad', 'ad_public.ad_id = ad.id')
            ->orderBy('ad_id');
            
        $command = $query->createCommand();
        return $command->queryAll();
    }
    
    public static function getCountById($id)
    {
        return self::find()
            ->where(['ad_id' => $id])
            ->count();
    }
}
