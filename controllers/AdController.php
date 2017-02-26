<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\controllers\ProtectedController;
use app\models\Ad;

class AdController extends ProtectedController
{
    public function actionIndex()
    {
        $ads = Ad::getAds();
        return $this->render('index', [
            'ads' => $ads,
        ]);
    }
}