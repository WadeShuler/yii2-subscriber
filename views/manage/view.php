<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Subscriber */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Subscribers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscriber-view">

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'email_address:email',
            //'phone_number',
            [
                'attribute' => 'phone_number',
                'value' => function ($model) {
                    if ( isset($model->phone_number) && ! empty($model->phone_number) ) {
                        return preg_replace('/\d{3}/', '$0-', str_replace('.', null, trim($model->phone_number)), 2);
                    }
                    return null;
                }
            ],
            'ip_address',
            //'email_marked_spam',
            [
                'attribute' => 'email_marked_spam',
                'value' => function ($model) {
                    return $model->email_marked_spam ? 'Yes' : 'No';
                }
            ],
            //'email_bounced',
            [
                'attribute' => 'email_bounced',
                'value' => function ($model) {
                    return $model->email_bounced ? 'Yes' : 'No';
                }
            ],
            //'email_unsubscribed',
            [
                'attribute' => 'email_unsubscribed',
                'value' => function ($model) {
                    return $model->email_unsubscribed ? 'Yes' : 'No';
                }
            ],
            'email_unsubscribed_at:datetime',
            //'sms_bounced',
            [
                'attribute' => 'sms_bounced',
                'value' => function ($model) {
                    return $model->sms_bounced ? 'Yes' : 'No';
                }
            ],
            //'sms_unsubscribed',
            [
                'attribute' => 'sms_unsubscribed',
                'value' => function ($model) {
                    return $model->sms_unsubscribed ? 'Yes' : 'No';
                }
            ],
            'sms_unsubscribed_at:datetime',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
