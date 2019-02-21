<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Subscriber */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscriber-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'sms_bounced')->dropDownList(
                [0 => 'No', 1 => 'Yes'],
                ['prompt'=>' - Bounced Status - ']
            ) ?>
        </div>

        <div class="col-sm-6">
            <?= $form->field($model, 'sms_unsubscribed')->dropDownList(
                [0 => 'No', 1 => 'Yes'],
                ['prompt'=>' - Unsubscribed Status - ']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'email_bounced')->dropDownList(
                [0 => 'No', 1 => 'Yes'],
                ['prompt'=>' - Bounced Status - ']
            ) ?>
        </div>

        <div class="col-sm-6">
            <?= $form->field($model, 'email_unsubscribed')->dropDownList(
                [0 => 'No', 1 => 'Yes'],
                ['prompt'=>' - Unsubscribed Status - ']
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
