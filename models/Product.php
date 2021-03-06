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
 * @property string $comment
 *
 * @property Category $category
 * @property ProductDetail $details
 */
class Product extends \yii\db\ActiveRecord
{
    public $sum;
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
            [['product_name', 'category_id'], 'required'],
            [['category_id', 'amount_supplied', 'amount_in_stock'], 'integer'],
            [['price_purchase'], 'number'],
            [['product_code'], 'string', 'max' => 32],
            [['product_name'], 'string', 'max' => 255],
            [['comment'], 'string'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'comment' => 'Comment'
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
    public function getProductSolds()
    {
        return $this->hasMany(ProductSold::className(), ['product_id' => 'id']);
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
            ->join('LEFT JOIN', 'product_detail', 'product.id=product_detail.inner_product_id')
            ->join('LEFT JOIN', 'site', 'product_detail.site_id=site.id')
            ->where(['category_id' => $categoryId]);
        
        $command = $query->createCommand();
        return $command->queryAll();
    }
    
    public static function getResiduePurchase($month)
    {
        $residue = 0;
        $products = self::find()->where(['!=', 'amount_supplied', '0'])->andWhere(['!=', 'price_purchase', '0'])->all();
        if ($products) {
            foreach ($products as $product) {
                $res = self::find()
                    ->select(['product.price_purchase * (product.amount_supplied - product_sold.amount) AS sum'])
                    ->joinWith(['productSolds'])
                    ->where(['product_sold.sale_date' => $month, 'product.id' => $product->id])
                    ->one();
                
                if (!$res) {
                    $res = self::find()
                        ->select(['`product`.`price_purchase` * `product`.`amount_supplied` AS sum'])
                        ->where(['id' => $product->id])
                        ->one();
                }
                
                $residue += $res->sum;
            }
        }
        return $residue;
    }
    
    public static function getProducts($categoryId)
    {
        return self::findAll([
            'category_id' => $categoryId
        ]);
    }
    
    public static function getProductById($id)
    {
        $query = new Query;
        $query->select([
                'product.*',
                'product_detail.*',
                'site.name'
            ])
            ->from('product')
            ->join('LEFT JOIN', 'product_detail', 'product.id=product_detail.inner_product_id')
            ->join('LEFT JOIN', 'site', 'product_detail.site_id=site.id')
            ->where(['product.id' => $id]);
        
        $command = $query->createCommand();
        return $command->queryOne();
    }
    
    public static function getProductsDetails($categoryId)
    {
        $query = new Query;
        $query->select([
                'product.*',
                'product_detail.*'
            ])
            ->from('product')
            ->join('LEFT JOIN', 'product_detail', 'product.id=product_detail.inner_product_id')
            ->where(['category_id' => $categoryId]);
        
        $command = $query->createCommand();
        return $command->queryAll();
    }
}
