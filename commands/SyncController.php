<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Sync;
use app\models\Product;
use app\models\Orders;
use app\models\Site;
use app\models\SyncSetting;
use app\models\Products;
use app\models\ProductPrices;

class SyncController extends Controller
{
    public function actionIndex()
    {
        set_time_limit(10000);
        echo "sync start" . "\n";
        
        //$products = Product::find()->all();
        $sites = Site::find()->all();
        foreach ($sites as $site) {
            echo "site - " . $site->name . "\n";
            Orders::$db = Yii::$app->get($site->cfg_alias);
            Products::$db = Yii::$app->get($site->cfg_alias);
            ProductPrices::$db = Yii::$app->get($site->cfg_alias);
            echo "db changed" . "\n";
            Sync::getNewProducts($site->id);
            echo "new products got" . "\n";
            $products = SyncSetting::getProductsBySite($site->id);
            echo "products got" . "\n";
            Sync::saveSold($products, $site->id);
            echo "sold saved" . "\n";
            Sync::savePrice($products, $site->id);
            Sync::saveReport($products, $site->id);
            echo "report saved" . "\n";
            Site::saveSyncDate($site->id);
            echo "date saved" . "\n";
        }
        
        $products = SyncSetting::getProductsUnique();
        Sync::saveAmount($products);
        echo "amount saved" . "\n";
        Sync::saveIncome($products);
        echo "income saved" . "\n";
        echo "sites update start" . "\n";
        foreach ($sites as $site) {
            echo "site - " . $site->name . "\n";
            $products = SyncSetting::getProductsBySite($site->id);
            echo "products got" . "\n";
            Products::$db = Yii::$app->get($site->cfg_alias);
            ProductPrices::$db = Yii::$app->get($site->cfg_alias);
            echo "db changed" . "\n";
            Sync::setChangesToSite($products, $site->id);
            echo "site - " . $site->name . " - updated" . "\n";
        }
        echo "sites update finish" . "\n";
        echo "sync finish";
    }
    
    
    
    
    
    
}
