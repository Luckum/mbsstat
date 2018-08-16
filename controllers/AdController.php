<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\helpers\Utils;
use app\controllers\ProtectedController;
use app\models\AdPublic;
use app\models\Ad;
use app\models\AdCategory;
use app\models\AdOther;

class AdController extends ProtectedController
{
    public function actionIndex($d = '', $all = '')
    {
        if (!empty($d)) {
            Yii::$app->params['thisMonth'] = $d;
        }
        $thisMonth = Yii::$app->params['thisMonth'];
        $adMonths = Ad::getAdMonthes();
        $otherAdMonths = AdOther::getAdMonthes();
        $statMonthes = array_merge($adMonths, $otherAdMonths);
        $statMonthes = Utils::unique_multidim_array($statMonthes, 'period');
        
        if (!empty($all) && $all == 1) {
            $ads = AdPublic::getAds();
        } else {
            $ads = AdPublic::getAds($thisMonth);
        }
        
        $adTotal = Ad::getAdTotal($thisMonth) + AdOther::getAdTotal($thisMonth);
        $adCanegories = AdCategory::find()->all();
        return $this->render('index', [
            'ads' => $ads,
            'adTotal' => $adTotal,
            'adCategories' => $adCanegories,
            'thisMonth' => $thisMonth,
            'statMonthes' => $statMonthes,
            'all' => $all
        ]);
    }
    
    public function actionCreate($cat_id)
    {
        $category = AdCategory::findOne($cat_id);
        if ($category->is_vk) {
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
                    $ad->paid_date = date('Y-m-d', strtotime($_POST['paid_date']));
                    $ad->next_pay_date = date('Y-m-d', strtotime($_POST['next_pay_date']));
                    $ad->period = date('Y-m-d', mktime(0, 0, 0, $_POST['period'], 1, date('Y')));
                    $ad->save();
                    $this->redirect(Url::to('ad/index', true));
                } else {
                    $ad = new Ad();
                    $ad->creator = $_POST['new_creator'];
                    $ad->price = $_POST['price'];
                    $ad->ad_type = $_POST['ad_type'];
                    $ad->amount = $_POST['amount'];
                    $ad->paid_date = date('Y-m-d', strtotime($_POST['paid_date']));
                    $ad->next_pay_date = date('Y-m-d', strtotime($_POST['next_pay_date']));
                    $ad->period = date('Y-m-d', mktime(0, 0, 0, $_POST['period'], 1, date('Y')));
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
        } else {
            if (isset($_POST['create'])) {
                if (!empty($_POST['name']) && !empty($_POST['price'])) {
                    $ad = new AdOther;
                    $ad->ad_category_id = $cat_id;
                    $ad->name = trim($_POST['name']);
                    $ad->price = $_POST['price'];
                    $ad->paid_date = date('Y-m-d', strtotime($_POST['paid_date']));
                    $ad->next_pay_date = date('Y-m-d', strtotime($_POST['next_pay_date']));
                    $ad->period = date('Y-m-d', mktime(0, 0, 0, $_POST['period'], 1, date('Y')));
                    if ($ad->save()) {
                        $this->redirect(Url::to('ad/index', true));
                    }
                }
            }
            return $this->render('create-other', [
                'category' => $category,
            ]);
        }
        
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
    
    public function actionUpdate($cat_id, $id)
    {
        $category = AdCategory::findOne($cat_id);
        if ($category->is_vk) {
            $ad = Ad::findOne($id);
            $ad_publics = AdPublic::find()->where(['ad_id' => $id])->all();
            if (isset($_POST['update'])) {
                $period = date('Y-m-d', mktime(0, 0, 0, $_POST['period'], 1, date('Y')));
                if ($ad->period != $period) {
                    $ad = new Ad;
                    $new = true;
                }
                $ad->creator = $_POST['creator'];
                $ad->price = $_POST['price'];
                $ad->amount = $_POST['amount'];
                $ad->ad_type = $_POST['ad_type'];
                $ad->paid_date = date('Y-m-d', strtotime($_POST['paid_date']));
                $ad->next_pay_date = date('Y-m-d', strtotime($_POST['next_pay_date']));
                $ad->period = $period;
                if ($ad->save()) {
                    if ($new) {
                        foreach ($_POST['public'] as $rec) {
                            $ad_public = new AdPublic;
                            $ad_public->ad_group = $rec;
                            $ad_public->ad_id = $ad->id;
                            $ad_public->save();
                        }
                    }
                    $this->redirect(Url::to('ad/index', true));
                }
            }
            return $this->render('update', [
                'ad' => $ad,
                'ad_publics' => $ad_publics,
            ]);
        } else {
            $ad = AdOther::findOne($id);
            if (isset($_POST['create'])) {
                if (!empty($_POST['name']) && !empty($_POST['price'])) {
                    $period = date('Y-m-d', mktime(0, 0, 0, $_POST['period'], 1, date('Y')));
                    if ($ad->period != $period) {
                        $ad = new AdOther;
                        $ad->ad_category_id = $cat_id;
                    }
                    $ad->name = trim($_POST['name']);
                    $ad->price = $_POST['price'];
                    $ad->paid_date = date('Y-m-d', strtotime($_POST['paid_date']));
                    $ad->next_pay_date = date('Y-m-d', strtotime($_POST['next_pay_date']));
                    $ad->period = $period;
                    if ($ad->save()) {
                        $this->redirect(Url::to('ad/index', true));
                    }
                }
            }
            return $this->render('update-other', [
                'category' => $category,
                'ad' => $ad,
            ]);
        }
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
    
    public function actionDeleteOther($id)
    {
        $ad = AdOther::findOne($id);
        if ($ad->delete()) {
            $this->redirect(Url::to('ad/index', true));
        }
    }
}