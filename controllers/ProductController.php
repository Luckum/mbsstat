<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\controllers\ProtectedController;
use app\models\Category;
use app\models\Product;
use app\models\ProductPrice;
use app\models\ProductDetail;
use app\models\Site;
use app\models\ProductSold;
use app\models\ProductRender;

class ProductController extends ProtectedController
{
    public function actionAccept()
    {
        $msg = '';
        if (isset($_POST['accept'])) {
            $add_amount = !empty($_POST['amount']) && is_numeric($_POST{'amount'}) ? $_POST['amount'] : 0;
            $product_id = $_POST['product'];
            if (isset($_POST['old_price']) && $_POST['old_price'] == 'Y') {
                $product = Product::findOne($product_id);
                $product->amount_supplied += $add_amount;
                if ($product->save()) {
                    $msg = 'Товар принят на склад.';
                }
            } else {
                $new_price = !empty($_POST['price']) && is_numeric($_POST['price']) ? $_POST['price'] : 0;
                if (isset($_POST['sold_old_price']) && $_POST['sold_old_price'] == 'Y') {
                    $product_old = Product::findOne($product_id);
                    $product = new ProductPrice();
                    $product->product_id = $product_id;
                    $product->old_price_purchase = $product_old->price_purchase;
                    $product->new_price_purchase = $new_price;
                    $product->amount = $add_amount;
                    if ($product->save()) {
                        $msg = 'Товар принят на склад';
                    }
                } else {
                    $product = Product::findOne($product_id);
                    $product->amount_supplied += $add_amount;
                    $product->price_purchase = $new_price;
                    if ($product->save()) {
                        $msg = 'Товар принят на склад';
                    }
                }
            }
        }
        
        $categories = Category::getCategories();
        return $this->render('accept', [
            'categories' => $categories,
            'msg' => $msg,
        ]);
    }
    
    public function actionGetproduct()
    {
        $products = [];
        if (isset($_POST['category']) && !empty($_POST['category'])) {
            $products = Product::getProducts($_POST['category']);
        }
        
        return $this->renderPartial('_product', [
            'products' => $products,
        ]);
    }
    
    public function actionGetdetails()
    {
        $details = [];
        if (isset($_POST['product']) && !empty($_POST['product'])) {
            $details = Product::getProductById($_POST['product']);
        }
        
        return $this->renderPartial('_detail', [
            'details' => $details,
        ]);
    }
    
    public function actionUpdateselling()
    {
        if (is_numeric($_POST['price_selling'])) {
            $product = ProductDetail::getDetailsBySite($_POST['site_id'], $_POST['product_id']);
            $product->price_selling = $_POST['price_selling'];
            if ($product->save()) {
                $site = Site::findOne($product->site_id);
                return $this->renderPartial('_price_selling_td', [
                    'product' => $product,
                    'sitename' => $site->name,
                ]);
            }
        }
    }
    
    public function actionUpdatecomment()
    {
        $product = ProductDetail::getDetailsBySite($_POST['site_id'], $_POST['product_id']);
        $product->comment = $_POST['comment'];
        if ($product->save()) {
            $site = Site::findOne($_POST['site_id']);
            return $this->renderPartial('_comment_td', [
                'product' => $product,
                'sitename' => $site->name,
            ]);
        }
    }
    
    public function actionUpdateincome()
    {
        $thisMonth = date("Y-m-01");
        $product = ProductDetail::getDetailsBySite($_POST['site_id'], $_POST['product_id']);
        $amount_sold = ProductSold::getTotalByProduct($product->id, $thisMonth);
        $income = number_format(sprintf("%01.2f", $product->price_selling * $amount_sold['amount']), 2, '.', ' ');
        return $income;
    }
    
    public function actionRender()
    {
        $msg = '';
        $thisMonth = date("Y-m-01");
        
        if (isset($_POST['render'])) {
            $product = new ProductRender();
            $product->amount = $_POST['amount'];
            $product->product_id = $_POST['product'];
            $product->render_price = $_POST['price'];
            $product->render_date = $thisMonth;
            if ($product->save()) {
                $msg = 'Товар списан';
            }
        }
        
        $categories = Category::getCategories();
        return $this->render('render', [
            'msg' => $msg,
            'categories' => $categories
        ]);
    }
    
    public function actionGetrenderdetails()
    {
        $details = [];
        if (isset($_POST['product']) && !empty($_POST['product'])) {
            $details = Product::getProductById($_POST['product']);
        }
        
        return $this->renderPartial('_render_detail', [
            'details' => $details,
        ]);
    }
}