<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%products_categories}}".
 *
 * @property string $product_id
 * @property string $category_id
 * @property string $link_type
 * @property integer $position
 */
class ProductsCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%products_categories}}';
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
            [['product_id', 'category_id'], 'required'],
            [['product_id', 'category_id', 'position'], 'integer'],
            [['link_type'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'category_id' => 'Category ID',
            'link_type' => 'Link Type',
            'position' => 'Position',
        ];
    }
    
    public static function getProductCategory($id)
    {
        $sql = 'SELECT category_id FROM ' . self::tableName() . ' WHERE product_id = :product_id AND link_type = "M"';
        return self::findBySql($sql, ['product_id' => $id])->one();
    }
}
