<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wadeshuler\subscriber\models\SmsCampaign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-campaign-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'testNumber')->textInput(['maxlength' => true, 'placeholder' => 'Example: 5554441234']) ?>
    <p class="help-block">Leave blank to send to everyone.</p>

    <p>&nbsp;</p>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
