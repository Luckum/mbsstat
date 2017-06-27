<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Sync;
use app\models\Product;
use app\models\Orders;
use app\models\Site;

class SyncController extends Controller
{
    public function actionIndex()
    {
        echo "sync start" . "\n";
        
        $products = Product::find()->all();
        $sites = Site::find()->all();
        foreach ($sites as $site) {
            Orders::$db = Yii::$app->get($site->cfg_alias);
            Sync::saveSold($products, $site->id);
            Sync::savePrice($products, $site->id);
            Site::saveSyncDate($site->id);
        }
        
        /*Orders::$db = Yii::$app->get('db_sd');
        $this->saveSold($products, 2);
        $this->savePrice($products, 2);*/
        
        Sync::saveIncome($products);
        echo "sync finish";
    }
    
    
    
    
    
    
}
