<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
$this->title = Yii::$app->params['name'];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="container login-box">
        <div class="row">
            <div class="col-md-offset-4 col-md-4">
                <div class="form-login">
                    <h4>Вход в <?= Yii::$app->params['name']; ?></h4>
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'options' => ['class' => 'form-horizontal']
                    ]); ?>
                    
                        <input type="text" id="userName" class="form-control input-sm chat-input" placeholder="логин" name="LoginForm[login]" />
                        </br>
                        <input type="password" id="userPassword" class="form-control input-sm chat-input" placeholder="пароль" name="LoginForm[password]" />
                        </br>
                        <div class="wrapper">
                            <span class="group-btn">     
                                <?= Html::submitButton('Войти <i class="fa fa-sign-in"></i>', ['class' => 'btn btn-primary btn-md', 'name' => 'login-button']) ?>
                            </span>
                        </div>                        
                        <?= $form->errorSummary($model); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>