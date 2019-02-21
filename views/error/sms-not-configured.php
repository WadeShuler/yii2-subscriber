<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel wadeshuler\subscriber\models\SmsCampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SMS Not Configured';
$this->params['breadcrumbs'][] = ['label' => 'Subscribers', 'url' => ['/subscriber']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-sms-not-configured">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Your SMS component is not supported, or not configured.</p>

    <p>
        The SMS component must be accessible via <code>Yii::$app->sms</code> and be used
        the same way the built-in Yii2 mail component works.
    </p>

<pre><code>
'components' => [
    'sms' => [
        // ...
        'messageConfig' => [
            'from' => '+15554441234',
        ],
    ],
],
</code></pre>

    <p>A great example is <a href="https://github.com/wadeshuler/yii2-sms-twilio">Yii2 SMS Twilio</a>.</p>
</div>
