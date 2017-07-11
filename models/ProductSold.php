<?php

namespace app\models;

use Yii;
use app\models\Site;
use yii\db\Query;

/**
 * This is the model class for table "product_sold".
 *
 * @property integer $id
 * @property string $sale_date
 * @property integer $product_id
 * @property integer $site_id
 * @property integer $amount
 *
 * @property Product $product
 * @property Site $site
 */
class ProductSold extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_sold';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_date', 'product_id', 'site_id', 'amount'], 'required'],
            [['sale_date'], 'safe'],
            [['product_id', 'site_id', 'amount'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['site_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_date' => 'Sale Date',
            'product_id' => 'Product ID',
            'site_id' => 'Site ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }
    
    public static function getTotalByProduct($id, $month)
    {
        return self::findAll([
            'product_id' => $id,
            'sale_date' => $month
        ]);
    }
    
    public static function getTotalBySite($p_id, $site_id, $month)
    {
        return self::findOne([
            'product_id' => $p_id,
            'site_id' => $site_id,
            'sale_date' => $month
        ]);
    }
    
    public static function getSumByProduct($id, $month)
    {
        return self::find()->where([
            'product_id' => $id,
            'sale_date' => $month
        ])->sum('amount');
    }
    
    public static function getSumByProductTotal($id)
    {
        return self::find()->where([
            'product_id' => $id,
        ])->sum('amount');
    }
    
    public static function getSoldTotal($p_id, $s_date)
    {
        $soldTotal = 0;
        $sites = Site::find()->all();
        foreach ($sites as $site) {
            $soldTotal += self::getSoldTotalBySite($p_id, $site->id, $s_date);
        }
        return $soldTotal;
    }
    
    public static function getSoldTotalSell($p_id, $s_date)
    {
        $soldTotal = 0;
        $sites = Site::find()->all();
        foreach ($sites as $site) {
            $soldTotal += self::getSoldTotalBySiteSell($p_id, $site->id, $s_date);
        }
        return $soldTotal;
    }
    
    public static function getSoldTotalBySite($p_id, $s_id, $month)
    {
        $query = new Query;
        $query->select([
                '(product_detail.price_selling - product.price_purchase) * product_sold.amount AS total'
            ])
            ->from('product_sold')
            ->join('LEFT JOIN', 'product_detail', 'product_detail.inner_product_id = product_sold.product_id AND product_sold.site_id = product_detail.site_id')
            ->join('LEFT JOIN', 'product', 'product.id = product_sold.product_id')
            ->where(['product_sold.sale_date' => $month, 'product_sold.product_id' => $p_id, 'product_sold.site_id' => $s_id]);
        
        $command = $query->createCommand();
        $total = $command->queryOne();
        return $total['total'];
    }
    
    public static function getSoldTotalBySiteSell($p_id, $s_id, $month)
    {
        $query = new Query;
        $query->select([
                '(product_detail.price_selling * product_sold.amount) AS total'
            ])
            ->from('product_sold')
            ->join('LEFT JOIN', 'product_detail', 'product_detail.inner_product_id = product_sold.product_id AND product_sold.site_id = product_detail.site_id')
            ->where(['product_sold.sale_date' => $month, 'product_sold.product_id' => $p_id, 'product_sold.site_id' => $s_id]);
        
        $command = $query->createCommand();
        $total = $command->queryOne();
        return $total['total'];
    }
    
    public static function getTopSold($month)
    {
        $query = new Query;
        $query->select([
                'product_sold.product_id, product.product_name, category.id, category.name, SUM(product_sold.amount) AS total'
            ])
            ->from('product_sold')
            ->join('LEFT JOIN', 'product', 'product.id = product_sold.product_id')
            ->join('LEFT JOIN', 'category', 'product.category_id = category.id')
            ->where(['product_sold.sale_date' => $month])
            ->groupBy('product_sold.product_id')
            ->orderBy('category.id, total DESC');
        
        $command = $query->createCommand();
        return $command->queryAll();
    }
    
    public static function getSumIncomeClear($id, $month)
    {
        return self::find()->where([
            'product_id' => $id,
            'sale_date' => $month
        ])->sum('income_clear_total');
    }
    
    public static function getSumIncome($id, $month)
    {
        return self::find()->where([
            'product_id' => $id,
            'sale_date' => $month
        ])->sum('income_total');
    }
    
    public static function getStatMonthes()
    {
        $query = new Query;
        $query->select([
            'sale_date'
        ])
        ->from('product_sold')
        ->groupBy('sale_date')
        ->orderBy('sale_date DESC');
        
        $command = $query->createCommand();
        return $command->queryAll();
    }
}
