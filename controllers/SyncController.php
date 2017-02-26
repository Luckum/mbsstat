<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\controllers\ProtectedController;
use app\models\Site;
use app\models\ProductDescriptions;
use app\models\Products;
use app\models\ProductsCategories;
use app\models\ProductPrices;
use app\models\ProductDetail;
use app\models\Product;
use app\models\ProductSold;
use app\models\Orders;

class SyncController extends ProtectedController
{
    public function actionIndex()
    {
        $sites = Site::find()->asArray()->all();
        return $this->render('index', [
            'sites' => $sites,
        ]);
    }
    
    public function actionSyncnow($id)
    {
        /*Yii::$app->db->createCommand()->truncateTable('product')->execute();
        Yii::$app->db->createCommand()->dropForeignKey('product_ibfk_2', 'product')->execute();
        Yii::$app->db->createCommand()->truncateTable('product_detail')->execute();
        Yii::$app->db->createCommand()->addForeignKey('product_ibfk_2', 'product', 'details_id', 'product_detail', 'id', 'CASCADE')->execute();
        
        $products = Products::getProducts();
        foreach ($products as $product) {
            $p_descr = ProductDescriptions::getProductName($product['product_id']);
            $p_category = ProductsCategories::getProductCategory($product['product_id']);
            $p_price = ProductPrices::getProductPrice($product['product_id']);
            
            $to_product_detail = new ProductDetail();
            $to_product_detail->site_id = $id;
            $to_product_detail->price_selling = $p_price['price'];
            $to_product_detail->product_id = $product['product_id'];
            $to_product_detail->save();
            
            $details_id = Yii::$app->db->getLastInsertID();
            
            $to_product = new Product();
            $to_product->product_code = $product['product_code'];
            $to_product->product_name = $p_descr['product'];
            $to_product->category_id = $p_category['category_id'];
            $to_product->amount_in_stock = $product['amount'];
            $to_product->details_id = $details_id;
            $to_product->save();
        }
        $site = Site::findOne($id);
        $site->last_sync_date = date('Y-m-d H:i:s');
        $site->save();
        */
        
        $thisMonth = date("Y-m-01");
        $products = Product::find()->all();
        foreach ($products as $product) {
            $total = Orders::getOrdersTotal($product['product_code']);
            
            $to_product_sold = new ProductSold();
            $to_product_sold->sale_date = $thisMonth;
            $to_product_sold->product_id = $product['id'];
            $to_product_sold->site_id = $id;
            $to_product_sold->amount = $total['total'];
            $to_product_sold->save();
        }
        
        $this->redirect(Url::to('sync/index', true));
    }
}