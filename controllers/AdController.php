<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\controllers\ProtectedController;
use app\models\AdPublic;
use app\models\Ad;

class AdController extends ProtectedController
{
    public function actionIndex()
    {
        $ads = AdPublic::getAds();
        $adTotal = Ad::getAdTotal();
        return $this->render('index', [
            'ads' => $ads,
            'adTotal' => $adTotal,
        ]);
    }
}