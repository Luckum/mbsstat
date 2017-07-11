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
    
    public function actionCreate()
    {
        $creators = Ad::find()->all();
        if (isset($_POST['create']) && $_POST['creator'] != 0) {
            if ($_POST['creator'] != '-1') {
                $ad_public = new AdPublic();
                $ad_public->ad_id = $_POST['creator'];
                $ad_public->ad_group = $_POST['public'];
                $ad_public->save();
                $ad = Ad::findOne($_POST['creator']);
                $ad->price = $_POST['price'];
                $ad->ad_type = $_POST['ad_type'];
                $ad->amount = $_POST['amount'];
                $ad->save();
                $this->redirect(Url::to('ad/index', true));
            } else {
                $ad = new Ad();
                $ad->creator = $_POST['new_creator'];
                $ad->price = $_POST['price'];
                $ad->ad_type = $_POST['ad_type'];
                $ad->amount = $_POST['amount'];
                $ad->save();
                $ad_public = new AdPublic();
                $ad_public->ad_id = $ad->id;
                $ad_public->ad_group = $_POST['public'];
                $ad_public->save();
                $this->redirect(Url::to('ad/index', true));
            }
        }
        return $this->render('create', [
            'creators' => $creators,
        ]);
    }
    
    public function actionGetprice()
    {
        $price = Ad::findOne($_POST['creator']);
        return $price['price'];
    }
    
    public function actionGetamount()
    {
        $price = Ad::findOne($_POST['creator']);
        return $price['amount'];
    }
    
    public function actionGettype()
    {
        $price = Ad::findOne($_POST['creator']);
        return $price['ad_type'];
    }
    
    public function actionUpdate($id)
    {
        $ad = Ad::findOne($id);
        $ad_publics = AdPublic::find()->where(['ad_id' => $id])->all();
        if (isset($_POST['update'])) {
            $ad->creator = $_POST['creator'];
            $ad->price = $_POST['price'];
            $ad->amount = $_POST['amount'];
            $ad->ad_type = $_POST['ad_type'];
            if ($ad->save()) {
                $this->redirect(Url::to('ad/index', true));
            }
        }
        return $this->render('update', [
            'ad' => $ad,
            'ad_publics' => $ad_publics,
        ]);
    }
    
    public function actionDeletepublic()
    {
        $public = AdPublic::findOne($_POST['id']);
        $public->delete();
        return true;
    }
    
    public function actionDelete($id)
    {
        $ad = Ad::findOne($id);
        if ($ad->delete()) {
            $this->redirect(Url::to('ad/index', true));
        }
    }
}