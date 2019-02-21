<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wadeshuler\subscriber\models\EmailCampaign */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Subscribers', 'url' => ['/subscriber']];
$this->params['breadcrumbs'][] = ['label' => 'Email Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="email-campaign-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    -->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'subject',
            'pretext',
            'message:ntext',
            'batch_id',
            'created_at:datetime',
        ],
    ]) ?>

</div>
