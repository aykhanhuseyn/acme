<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app','Register');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?=Yii::t('app','Please fill out the following fields to register:')?></p>

    <?php $registerForm = ActiveForm::begin([
        'id' => 'register-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $registerForm->errorSummary($newUser) ?>
    <?= $registerForm->field($newUser, 'username')->textInput(['autofocus' => true]) ?>
    <?= $registerForm->field($newUser, 'email')->textInput() ?>
    <?= $registerForm->field($newUser, 'password')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(Yii::t('app','Register'), ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        <?= Yii::t('app','If you are already registered please, {login}',[ 'login' => Html::a(Yii::t('app','pls-login'),['/site/login']) ])?>.
    </div>
</div>
