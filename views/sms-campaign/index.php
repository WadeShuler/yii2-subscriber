<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel wadeshuler\subscriber\models\SmsCampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SMS Campaigns';
$this->params['breadcrumbs'][] = ['label' => 'Subscribers', 'url' => ['/subscriber']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-campaign-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Sms Campaign', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'message:ntext',
            [
                'attribute' => 'message',
                'format' => 'text',
                'value' => function($dataProvider) {
                    return StringHelper::truncateWords($dataProvider->message, 10);
                }
            ],
            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
