<?php

namespace app\models;

use Yii;
use app\models\Orders;
use app\models\ProductSold;
use app\models\ProductPrices;
use app\models\ProductDetail;
use app\models\Income;
use app\models\ProductRender;
use app\models\Product;

class Sync extends yii\base\Model
{
    public static function saveSold($products, $site_id)
    {
        $thisMonth = Yii::$app->params['thisMonth'];
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
                $product_details = ProductDetail::getDetailsBySite($site_id, $product->id);
                $product_sold->income_clear_total = ($product_details->price_selling - $product->price_purchase) * $total['total'];
                $product_sold->income_total = $product_details->price_selling * $total['total'];
                $product_sold->amount = $total['total'];
                $product_sold->save();
            }
        }
    }
    
    public static function savePrice($products, $site_id)
    {
        foreach ($products as $product) {
            $price = ProductPrices::getProductPriceByCode($product->product_code);
            if ($price) {
                $product_detail = ProductDetail::findOne([
                    'inner_product_id' => $product->id,
                    'site_id' => $site_id
                ]);
                
                if (!$product_detail) {
                    $product_detail = new ProductDetail();
                    $product_detail->inner_product_id = $product->id;
                    $product_detail->site_id = $site_id;
                }
                $product_detail->price_selling = $price['price'];
                $product_detail->product_id = $price['product_id'];
                $product_detail->save();
            }
        }
    }
    
    public static function saveIncome($products)
    {
        $totalRevenue = $totalRevenueClear = 0;
        $thisMonth = Yii::$app->params['thisMonth'];
        foreach ($products as $product) {
            $amount_sold = ProductSold::getTotalByProduct($product->id, $thisMonth);
            if ($amount_sold) {
                foreach ($amount_sold as $amount) {
                    $product_detail = ProductDetail::getDetailsBySite($amount->site_id, $amount->product_id);
                    $totalRevenue +=  $product_detail->price_selling * $amount->amount;
                    $totalRevenueClear +=  ($product_detail->price_selling - $product->price_purchase) * $amount->amount;
                }
                $render = ProductRender::getRenderTotal($product->id, $thisMonth);
                if ($render) {
                    $totalRevenue += $render['render_price'] * $render['amount'];
                    $totalRevenueClear += ($render['render_price'] - $product->price_purchase) * $render['amount'];
                }
            }
        }
        Income::setIncomes($thisMonth, $totalRevenue, $totalRevenueClear);
    }
    
    public static function saveReport($products, $site_id)
    {
        $thisMonth = Yii::$app->params['thisMonth'];
        foreach ($products as $product) {
            $product_detail = ProductDetail::findOne([
                'inner_product_id' => $product->id,
                'site_id' => $site_id
            ]);
            
            $product_detail->income_clear = $product_detail->price_selling - $product->price_purchase;
            $product_detail->save();
        }
    }
    
    public static function saveAmount($products)
    {
        foreach ($products as $product) {
            $product_sold_total = ProductSold::getSumByProductTotal($product['id']);
            $prod = Product::findOne($product['id']);
            $prod->amount_in_stock = $product['amount_supplied'] - $product_sold_total;
            $prod->save();
            
        }
    }
}