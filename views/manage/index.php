<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subscribers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscriber-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="fa fa-plus"></i>&nbsp; Subscriber', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-download"></i>&nbsp; Import', ['import'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-envelope-o"></i>&nbsp; Email', ['email-campaign/create'], ['class' => 'btn btn-info', 'style' => 'padding-left:15px;padding-right:15px;']) ?>
        <?= Html::a('<i class="fa fa-commenting-o"></i>&nbsp; SMS', ['sms-campaign/create'], ['class' => 'btn btn-warning', 'style' => 'padding-left:15px;padding-right:15px;']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'email_address:email',
            //'phone_number',
            [
                'attribute' => 'phone_number',
                'format' => 'text',
                'value' => function ($data) {
                    if ( isset($data->phone_number) && ! empty($data->phone_number) ) {
                        return preg_replace('/\d{3}/', '$0-', str_replace('.', null, trim($data->phone_number)), 2);
                    }
                    return null;
                }
            ],
            // 'ip_address',
            // 'email_marked_spam',
            'email_bounced:boolean',
            'email_unsubscribed:boolean',
            // 'email_unsubscribed_at',
            'sms_bounced:boolean',
            'sms_unsubscribed:boolean',
            // 'sms_unsubscribed_at',
            'created_at:datetime',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
