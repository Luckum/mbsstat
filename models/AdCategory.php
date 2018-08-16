<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ad_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_vk
 *
 * @property AdOther[] $adOthers
 */
class AdCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ad_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_vk'], 'safe'],
            [['name'], 'string', 'max' => 100],
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
    public function getAdOthers()
    {
        return $this->hasMany(AdOther::className(), ['ad_category_id' => 'id']);
    }
}
