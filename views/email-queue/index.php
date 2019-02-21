<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel wadeshuler\subscriber\models\EmailQueueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Email Queues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-queue-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--
    <p>
        <?= Html::a('Create Email Queue', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'campaign_id',
            'subscriber_id',
            'data:ntext',
            'attempts',
            'last_attempt_time',
            'time_to_send',
            'sent_time',
            'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
    
</div>
