<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%category_descriptions}}".
 *
 * @property string $category_id
 * @property string $lang_code
 * @property string $category
 * @property string $description
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $page_title
 * @property string $age_warning_message
 */
class CategoryDescriptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_descriptions}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_mbs');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'lang_code'], 'required'],
            [['category_id'], 'integer'],
            [['description', 'age_warning_message'], 'string'],
            [['lang_code'], 'string', 'max' => 2],
            [['category', 'meta_keywords', 'meta_description', 'page_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'lang_code' => 'Lang Code',
            'category' => 'Category',
            'description' => 'Description',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'page_title' => 'Page Title',
            'age_warning_message' => 'Age Warning Message',
        ];
    }
}
