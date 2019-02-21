<?php

namespace wadeshuler\subscriber\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use wadeshuler\subscriber\models\Subscriber;
use wadeshuler\subscriber\models\EmailCampaign;
use wadeshuler\subscriber\models\EmailCampaignSearch;

/**
 * EmailCampaignController implements the CRUD actions for EmailCampaign model.
 */
class EmailCampaignController extends Controller
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
        if ( ! $this->isEmailConfigured() ) {
            return $this->redirect(['error/email-not-configured']);
        }

        return true;
    }

    private function isEmailConfigured()
    {
        if ( isset(Yii::$app->mailer) ) {
            return ( isset(Yii::$app->mailer->messageConfig['from'], Yii::$app->mailer->messageConfig['replyTo']) && ! empty(Yii::$app->mailer->messageConfig['from']) && ! empty(Yii::$app->mailer->messageConfig['replyTo']) ) ? true : false;
        }

        return false;
    }

    /**
     * Lists all EmailCampaign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailCampaignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmailCampaign model.
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
     * Creates a new EmailCampaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmailCampaign();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $campaignId = $model->id;

            if ( $batchId = $this->createBatchId() ) {
                $model->batch_id = $batchId;
                $model->update();
            }

            foreach (Subscriber::find()->select(['id'])->where(['>=', 'id', 0])->andWhere(['email_marked_spam' => 0, 'email_unsubscribed' => 0, 'email_bounced' => 0])->andWhere(['not', ['email_address' => null]])->orderBy(['id' => SORT_ASC])->batch(250) as $subscribers)
            {
                $this->queue($campaignId, $subscribers);
            }

            Yii::$app->session->setFlash('success', 'Your message has been queued!');

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    private function createBatchId()
    {
        if ( Yii::$app->mailer->useFileTransport === false ) {
            if ( method_exists(Yii::$app->mailer, 'createBatchId') && is_callable([Yii::$app->mailer, 'createBatchId']) ) {
                $batchId = Yii::$app->mailer->createBatchId();
                if ( isset($batchId) && ! empty($batchId) ) {
                    return $batchId;
                }
            }
        }

        return false;
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
            '{{%email_queue}}',
            ['campaign_id', 'subscriber_id', 'time_to_send', 'created_at'],
            $finalRecipients
        )->execute();
    }

    /**
     * Finds the EmailCampaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmailCampaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmailCampaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
