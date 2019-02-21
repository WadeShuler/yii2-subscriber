<?php

namespace wadeshuler\subscriber\controllers;

use Yii;
use wadeshuler\subscriber\models\SmsQueue;
use wadeshuler\subscriber\models\SmsQueueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmsQueueController implements the CRUD actions for SmsQueue model.
 */
class SmsQueueController extends Controller
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
     * Lists all SmsQueue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsQueueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC]
        ]);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmsQueue model.
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
     * Finds the SmsQueue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SmsQueue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SmsQueue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
