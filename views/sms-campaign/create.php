<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wadeshuler\subscriber\models\SmsCampaign */

$this->title = 'Create Sms Campaign';
$this->params['breadcrumbs'][] = ['label' => 'Subscribers', 'url' => ['/subscriber']];
$this->params['breadcrumbs'][] = ['label' => 'SMS Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-campaign-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
