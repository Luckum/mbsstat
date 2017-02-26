<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $product_code
 * @property string $product_name
 * @property integer $category_id
 * @property string $price_purchase
 * @property integer $amount_supplied
 * @property integer $amount_in_stock
 * @property integer $details_id
 *
 * @property Category $category
 * @property ProductDetail $details
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_name', 'category_id', 'amount_in_stock', 'details_id'], 'required'],
            [['category_id', 'amount_supplied', 'amount_in_stock', 'details_id'], 'integer'],
            [['price_purchase'], 'number'],
            [['product_code'], 'string', 'max' => 32],
            [['product_name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['details_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductDetail::className(), 'targetAttribute' => ['details_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_code' => 'Product Code',
            'product_name' => 'Product Name',
            'category_id' => 'Category ID',
            'price_purchase' => 'Price Purchase',
            'amount_supplied' => 'Amount Supplied',
            'amount_in_stock' => 'Amount In Stock',
            'details_id' => 'Details ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetails()
    {
        return $this->hasOne(ProductDetail::className(), ['id' => 'details_id']);
    }
    
    public static function getProductsByCategory($categoryId)
    {
        $query = new Query;
        $query->select([
                'product.*',
                'product_detail.*',
                'site.name'
            ])
            ->from('product')
            ->join('LEFT JOIN', 'product_detail', 'product.details_id=product_detail.id')
            ->join('LEFT JOIN', 'site', 'product_detail.site_id=site.id')
            ->where(['category_id' => $categoryId]);
        
        $command = $query->createCommand();
        return $command->queryAll();
    }
    
    public static function getResiduePurchase($month)
    {
        $query = new Query;
        $query->select([
                'SUM(product.price_purchase * (product.amount_supplied - product_sold.amount)) AS sum'
            ])
            ->from('product')
            ->join('LEFT JOIN', 'product_sold', 'product_sold.product_id = product.id')
            ->where(['product_sold.sale_date' => $month]);
        
        $command = $query->createCommand();
        $residue = $command->queryOne();
        return $residue['sum'];
    }
    
}
