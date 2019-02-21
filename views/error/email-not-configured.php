<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel wadeshuler\subscriber\models\SmsCampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Email Not Configured';
$this->params['breadcrumbs'][] = ['label' => 'Subscribers', 'url' => ['/subscriber']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-email-not-configured">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Your Email component is not supported, or not configured.</p>

    <p>
        The Email component must be accessible via <code>Yii::$app->mailer</code> and have
        the <code>from</code> and <code>replyTo</code> params configured within the
        <code>messageConfig</code> array.
    </p>

<pre><code>
'components' => [
    'mailer' => [
        // ...
        'messageConfig' => [
            'from' => ['admin@example.com' => 'Company Name'],
            'replyTo' => 'noreply@example.com',
        ],
    ],
],
</code></pre>

</div>
