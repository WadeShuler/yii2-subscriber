<?php

namespace wadeshuler\subscriber\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use wadeshuler\subscriber\models\SmsCampaign;
use wadeshuler\subscriber\models\SmsCampaignSearch;
use wadeshuler\subscriber\models\Subscriber;

/**
 * SmsCampaignController implements the CRUD actions for SmsCampaign model.
 */
class SmsCampaignController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ( ! $this->isSmsConfigured() ) {
            return $this->redirect(['error/sms-not-configured']);
        }

        return true;
    }

    private function isSmsConfigured()
    {
        if ( isset(Yii::$app->sms) ) {
            return ( isset(Yii::$app->sms->messageConfig['from']) && ! empty(Yii::$app->sms->messageConfig['from']) ) ? true : false;
        }

        return false;
    }

    /**
     * Lists all SmsCampaign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsCampaignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmsCampaign model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SmsCampaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SmsCampaign();

        if ($model->load(Yii::$app->request->post()) )
        {

            if ( isset($model->testNumber) && ! empty($model->testNumber) )
            {

                if ( $result = $this->sendTestSms($model->testNumber, $model->message) ) {
                    Yii::$app->session->setFlash('success', 'Your test message has been sent!');
                    $model->testNumber = null;
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error sending your test message!');
                }

            } else {

                if ( $model->save() )
                {
                    $campaignId = $model->id;

                    foreach (Subscriber::find()->select(['id'])->where(['>=', 'id', 0])->andWhere(['sms_unsubscribed' => 0, 'sms_bounced' => 0])->andWhere(['not', ['phone_number' => null]])->orderBy(['id' => SORT_ASC])->batch(250) as $subscribers)
                    {
                        $this->queue($campaignId, $subscribers);
                    }

                    Yii::$app->session->setFlash('success', 'Your message has been queued!');

                    return $this->redirect(['view', 'id' => $model->id]);
                }

            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    private function sendTestSms($number, $message)
    {
        $module = Yii::$app->getModule('subscriber');

        $domainUrl = ( isset($module->domainUrl) && ! empty($module->domainUrl) ) ? $module->domainUrl : '';
        $name = 'Joe Tester';
        $userId = '0';
        $email = 'joetester@example.com';

        $message = str_replace('-domainUrl-', $domainUrl, $message);
        $message = str_replace('-name-', $name, $message);
        $message = str_replace('-userId-', $userId, $message);
        $message = str_replace('-email-', $email, $message);

        return Yii::$app->sms->compose()
            ->setTo('+1' . $number)
            ->setMessage($message)
            ->send();
    }

    /**
     * Enqueue the message storing it in database.
     *
     * @param int $campaignId The campaign model id
     * @param array $subscribers Array of Subscriber objects
     * @param timestamp $time_to_send
     * @return boolean true on success, false otherwise
     */
    public function queue($campaignId, $subscribers, $time_to_send = 'now')
    {
        if ( ! is_array($subscribers) ) {
            return false;
        }

        $currentTime = time();      // for consistency

        if ($time_to_send == 'now') {
            $time_to_send = date('Y-m-d H:i:s', $currentTime); //time();
        }

        $created_at = date('Y-m-d H:i:s', $currentTime);

        $finalRecipients = [];

        foreach ( $subscribers as $subscriber) {
            $finalRecipients[] = [$campaignId, $subscriber->id, $time_to_send, $created_at];
        }

        return Yii::$app->db->createCommand()->batchInsert(
            '{{%sms_queue}}',
            ['campaign_id', 'subscriber_id', 'time_to_send', 'created_at'],
            $finalRecipients
        )->execute();
    }

    /**
     * Finds the SmsCampaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SmsCampaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SmsCampaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
