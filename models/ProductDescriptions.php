<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product_descriptions}}".
 *
 * @property string $product_id
 * @property string $lang_code
 * @property string $product
 * @property string $shortname
 * @property string $short_description
 * @property string $full_description
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $search_words
 * @property string $page_title
 * @property string $age_warning_message
 * @property string $promo_text
 */
class ProductDescriptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_descriptions}}';
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
            [['product_id', 'lang_code'], 'required'],
            [['product_id'], 'integer'],
            [['short_description', 'full_description', 'search_words', 'age_warning_message', 'promo_text'], 'string'],
            [['lang_code'], 'string', 'max' => 2],
            [['product', 'shortname', 'meta_keywords', 'meta_description', 'page_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'lang_code' => 'Lang Code',
            'product' => 'Product',
            'shortname' => 'Shortname',
            'short_description' => 'Short Description',
            'full_description' => 'Full Description',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'search_words' => 'Search Words',
            'page_title' => 'Page Title',
            'age_warning_message' => 'Age Warning Message',
            'promo_text' => 'Promo Text',
        ];
    }
    
    public static function getProductName($id)
    {
        $sql = 'SELECT product FROM ' . self::tableName() . ' WHERE product_id = :product_id';
        return self::findBySql($sql, ['product_id' => $id])->one();
    }
}
