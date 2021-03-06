<?php

namespace app\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;

class ProtectedController extends Controller
{
    public $publicActions = [
        'login',
    ];
    
    public function init() {
        parent::init();
    }
    
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            if (!in_array($action->id, $this->publicActions)) return $this->redirect(Url::to(Yii::$app->user->loginUrl, true));
        } else {
            
        }
        return parent::beforeAction($action);
    }
}