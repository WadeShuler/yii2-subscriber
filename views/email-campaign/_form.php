<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use wadeshuler\ckeditor\widgets\CKEditor;

/* @var $this yii\web\View */
/* @var $model wadeshuler\subscriber\models\EmailCampaign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-campaign-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'testEmail')->textInput(['maxlength' => true, 'placeholder' => 'Example: you@example.com']) ?>
    <p class="help-block">Leave blank to send to everyone.</p>

    <p>&nbsp;</p>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pretext')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message')->widget(CKEditor::className()) ?>

    <?php //$form->field($model, 'batch_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
