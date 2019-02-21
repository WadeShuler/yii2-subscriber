<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wadeshuler\subscriber\models\EmailQueueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-queue-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'campaign_id') ?>

    <?= $form->field($model, 'subscriber_id') ?>

    <?= $form->field($model, 'data') ?>

    <?= $form->field($model, 'attempts') ?>

    <?php // echo $form->field($model, 'last_attempt_time') ?>

    <?php // echo $form->field($model, 'time_to_send') ?>

    <?php // echo $form->field($model, 'sent_time') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
