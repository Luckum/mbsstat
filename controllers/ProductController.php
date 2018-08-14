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
use app\models\Sync;
use app\models\Income;

class ProductController extends ProtectedController
{
    public function actionAccept()
    {
        $msg = '';
        $thisMonth = Yii::$app->params['thisMonth'];
        if (isset($_POST['accept'])) {
            $add_amount = !empty($_POST['amount']) && is_numeric($_POST{'amount'}) ? $_POST['amount'] : 0;
            $product_id = $_POST['product'];
            if (isset($_POST['old_price']) && $_POST['old_price'] == 'Y') {
                $product = Product::findOne($product_id);
                $product->amount_supplied += $add_amount;
                $product->amount_in_stock += $add_amount;
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
                        $product_old->amount_supplied += $add_amount;
                        $product_old->amount_in_stock += $add_amount;
                        if ($product_old->save()) {
                            $msg = 'Товар принят на склад';
                        }
                    }
                } else {
                    $product = Product::findOne($product_id);
                    $product->amount_supplied += $add_amount;
                    $product->amount_in_stock += $add_amount;
                    $product->price_purchase = $new_price;
                    if ($product->save()) {
                        $sites = Site::find()->all();
                        foreach ($sites as $site) {
                            $product_detail = ProductDetail::findOne([
                                'inner_product_id' => $product->id,
                                'site_id' => $site->id
                            ]);
                            $product_detail->income_clear = $product_detail->price_selling - $product->price_purchase;
                            $product_detail->save();
                            
                            $product_sold = ProductSold::findOne([
                                'product_id' => $product->id,
                                'sale_date' => $thisMonth,
                                'site_id' => $site->id
                            ]);
                            if ($product_sold) {
                                $product_sold->income_clear_total = ($product_detail->price_selling - $product->price_purchase) * $product_sold->amount;
                                $product_sold->save();
                            }
                        }
                        $products = Product::find()->all();
                        Sync::saveIncome($products);
                        $incomes = Income::getIncomesByMonth($thisMonth);
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
                $p = Product::findOne($_POST['product_id']);
                return $this->renderPartial('_price_selling_td', [
                    'product' => $product,
                    'sitename' => $site->name,
                    'p' => $p,
                ]);
            }
        }
    }
    
    public function actionUpdatecomment()
    {
        $product = Product::findOne($_POST['product_id']);
        $product->comment = $_POST['comment'];
        if ($product->save()) {
            return $this->renderPartial('_comment_td', [
                'product' => $product,
            ]);
        }
    }
    
    public function actionUpdateincome()
    {
        $thisMonth = Yii::$app->params['thisMonth'];
        if (is_numeric($_POST['price_selling'])) {
            $sites = Site::find()->all();
            $product = Product::findOne($_POST['product_id']);
            foreach ($sites as $site) {
                $product_details = ProductDetail::getDetailsBySite($site->id, $product->id);
                $product_sold = ProductSold::findOne([
                    'product_id' => $product->id,
                    'sale_date' => $thisMonth,
                    'site_id' => $site->id
                ]);
                if ($product_sold) {
                    $product_sold->income_total = $product_details->price_selling * $product_sold->amount;
                    $product_sold->save();
                }
            }
            return $this->renderPartial('_income_td', [
                'sites' => $sites,
                'product' => $product,
            ]);
        }
    }
    
    public function actionRender()
    {
        $msg = '';
        $thisMonth = Yii::$app->params['thisMonth'];
        
        if (isset($_POST['render'])) {
            $product = new ProductRender();
            $product->amount = $_POST['amount'];
            $product->product_id = $_POST['product'];
            $product->render_price = $_POST['price'];
            $product->render_date = $thisMonth;
            if ($product->save()) {
                $prod = Product::findOne($_POST['product']);
                $prod->amount_in_stock = $prod->amount_in_stock - $_POST['amount'];
                $prod->save();
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
    
    public function actionUpdatepurchase()
    {
        if (is_numeric($_POST['price_purchase'])) {
            $product = Product::findOne($_POST['product_id']);
            $product->price_purchase = $_POST['price_purchase'];
            if ($product->save()) {
                return $this->renderPartial('_price_purchase_td', [
                    'product' => $product,
                ]);
            }
        }
    }
    
    public function actionUpdateincomeclear()
    {
        if (is_numeric($_POST['product_id'])) {
            $sites = Site::find()->all();
            $product = Product::findOne($_POST['product_id']);
            foreach ($sites as $site) {
                $product_detail = ProductDetail::findOne([
                    'inner_product_id' => $product->id,
                    'site_id' => $site->id
                ]);
                if ($product_detail) {
                    $product_detail->income_clear = $product_detail->price_selling - $product->price_purchase;
                    $product_detail->save();
                }
            }
            return $this->renderPartial('_income_clear_td', [
                'sites' => $sites,
                'product' => $product,
            ]);
        }
    }
    
    public function actionUpdateincomecleartotal()
    {
        $thisMonth = Yii::$app->params['thisMonth'];
        if (is_numeric($_POST['product_id'])) {
            $sites = Site::find()->all();
            $product = Product::findOne($_POST['product_id']);
            foreach ($sites as $site) {
                $product_details = ProductDetail::getDetailsBySite($site->id, $product->id);
                $product_sold = ProductSold::findOne([
                    'product_id' => $product->id,
                    'sale_date' => $thisMonth,
                    'site_id' => $site->id
                ]);
                if ($product_sold) {
                    $product_sold->income_clear_total = ($product_details->price_selling - $product->price_purchase) * $product_sold->amount;
                    $product_sold->save();
                }
            }
            
            return $this->renderPartial('_income_clear_total_td', [
                'sites' => $sites,
                'product' => $product,
            ]);
        }
    }
    
    public function actionUpdaterevenue()
    {
        $thisMonth = Yii::$app->params['thisMonth'];
        $products = Product::find()->all();
        Sync::saveIncome($products);
        $incomes = Income::getIncomesByMonth($thisMonth);
        return $this->renderPartial('_revenue_tbl', [
            'incomes' => $incomes,
        ]);
    }
    
    public function actionUpdatecashbox()
    {
        return $this->renderPartial('_cashbox_container', [
            'cashbox' => Income::calcCashbox(),
        ]);
    }
    
    public function actionUpdateresiduepurchase()
    {
        $thisMonth = Yii::$app->params['thisMonth'];
        $residuePurchase = Product::getResiduePurchase($thisMonth);
        return $this->renderPartial('_residue_purchase_td', [
            'residuePurchase' => $residuePurchase,
        ]);
    }
    
    public function actionUpdateresiduetotal()
    {
        $thisMonth = Yii::$app->params['thisMonth'];
        $residuePurchase = Product::getResiduePurchase($thisMonth);
        $residueDebt = 0;
        return $this->renderPartial('_residue_total_td', [
            'residuePurchase' => $residuePurchase,
            'residueDebt' => $residueDebt,
            'cashbox' => Income::calcCashbox(),
        ]);
    }
    
    public function actionRenderlist()
    {
        $products = ProductRender::find()->with('product')->with('product.category')->orderBy('render_date DESC')->all();
        return $this->render('renderlist', [
            'products' => $products,
        ]);
    }
}