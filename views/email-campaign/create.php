<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wadeshuler\subscriber\models\EmailCampaign */

$this->title = 'Create Email Campaign';
$this->params['breadcrumbs'][] = ['label' => 'Subscribers', 'url' => ['/subscriber']];
$this->params['breadcrumbs'][] = ['label' => 'Email Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-campaign-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
