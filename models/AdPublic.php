<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ad_public".
 *
 * @property integer $id
 * @property string $ad_group
 *
 * @property Ad[] $ads
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
            [['ad_group'], 'required'],
            [['ad_group'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ad::className(), ['ad_public_id' => 'id']);
    }
}
