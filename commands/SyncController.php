<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Orders;
use app\models\Product;
use app\models\ProductSold;
use app\models\ProductPrices;

class SyncController extends Controller
{
    public function actionIndex()
    {
        echo "sync start" . "\n";
        
        $products = Product::find()->all();
        Orders::$db = Yii::$app->get('db_mbs');
        $this->saveSold($products, 1);
        Orders::$db = Yii::$app->get('db_sd');
        $this->saveSold($products, 2);
    }
    
    protected function saveSold($products, $site_id)
    {
        $thisMonth = date("Y-m-01");
        foreach ($products as $product) {
            $total = Orders::getOrdersTotal($product->product_code);
            if ($total) {
                $product_sold = ProductSold::findOne([
                    'product_id' => $product->id,
                    'sale_date' => $thisMonth,
                    'site_id' => $site_id
                ]);
                if (!$product_sold) {
                    $product_sold = new ProductSold();
                    $product_sold->product_id = $product->id;
                    $product_sold->sale_date = $thisMonth;
                    $product_sold->site_id = $site_id;
                }
                $product_sold->amount = $total['total'];
                $product_sold->save();
            }
        }
    }
    
    protected function savePrice($products, $site_id)
    {
        foreach ($products as $product) {
            $price = ProductPrices::getProductPriceByCode($product->product_code);
        }
    }
}
