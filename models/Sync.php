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
use app\models\Products;
use app\models\ProductDescriptions;
use app\models\ProductsCategories;

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
                if ($product_details) {
                    //$product_sold->income_clear_total = ($product_details->price_selling - $product->price_purchase) * $total['total'];
                    $product_sold->income_clear_total = $total['total_price'] - $product->price_purchase * $total['total'];
                    //$product_sold->income_total = $product_details->price_selling * $total['total'];
                    $product_sold->income_total = $total['total_price'];
                    $product_sold->amount = $total['total'];
                    $product_sold->save();
                }
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
                    $totalRevenue +=  $amount->income_total;
                    $totalRevenueClear +=  $amount->income_clear_total;
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
            
            if ($product_detail) {
                $product_detail->income_clear = $product_detail->price_selling - $product->price_purchase;
                $product_detail->save();
            }
        }
    }
    
    public static function saveAmount($products)
    {
        foreach ($products as $product) {
            $product_sold_total = ProductSold::getSumByProductTotal($product['id']);
            $product_render_total = ProductRender::getSumRenderByProduct($product['id']);
            $prod = Product::findOne($product['id']);
            $prod->amount_in_stock = $product['amount_supplied'] - $product_sold_total - $product_render_total;
            $prod->save();
            
        }
    }
    
    public static function getNewProducts($site_id)
    {
        $products = Products::getProducts();
        foreach ($products as $k => $product) {
            if (!Product::find()->where(['product_code' => $product['product_code']])->exists()) {
                $p_descr = ProductDescriptions::getProductName($product['product_id']);
                $p_category = ProductsCategories::getProductCategory($product['product_id']);
                $p_price = ProductPrices::getProductPriceByCode($product['product_code']);
                
                $to_product = new Product();
                $to_product->product_code = $product['product_code'];
                $to_product->product_name = $p_descr['product'];
                $to_product->category_id = $p_category['category_id'];
                $to_product->amount_supplied = $product['amount'];
                $to_product->save();
                
                $product_id = Yii::$app->db->getLastInsertID();
                
                $to_product_detail = new ProductDetail();
                $to_product_detail->site_id = $site_id;
                $to_product_detail->price_selling = $p_price['price'];
                $to_product_detail->product_id = $product['product_id'];
                $to_product_detail->inner_product_id = $product_id;
                $to_product_detail->save();
            } else {
                $prod = Product::find()->where(['product_code' => $product['product_code']])->one();
                if (!ProductDetail::find()->where(['site_id' => $site_id, 'inner_product_id' => $prod->id])->exists()) {
                    $p_price = ProductPrices::getProductPriceByCode($product['product_code']);
                    
                    $to_product_detail = new ProductDetail();
                    $to_product_detail->site_id = $site_id;
                    $to_product_detail->price_selling = $p_price['price'];
                    $to_product_detail->product_id = $product['product_id'];
                    $to_product_detail->inner_product_id = $prod->id;
                    $to_product_detail->save();
                }
                $p_descr = ProductDescriptions::getProductName($product['product_id']);
                if ($prod->product_name != $p_descr['product']) {
                    $prod->product_name = $p_descr['product'];
                    $prod->save();
                }
            }
        }
    }
    
    public static function setChangesToSite($products, $site_id)
    {
        foreach ($products as $product) {
            $prod = Products::find()->where(['product_code' => $product['product_code']])->one();
            if ($prod) {
                if ($prod->amount != $product['amount_in_stock']) {
                    Products::setAmount($product['product_code'], $product['amount_in_stock']);
                }
                $prod_price = ProductPrices::find()->where(['product_id' => $prod->product_id])->one();
                $product_detail = ProductDetail::getDetailsBySite($site_id, $product['id']);
                if ($prod_price->price != $product_detail->price_selling) {
                    $prod_price->price = $product_detail->price_selling;
                    $prod_price->save();
                }
            }
        }
    }
}