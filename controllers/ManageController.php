<?php

namespace wadeshuler\subscriber\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use wadeshuler\subscriber\models\ImportForm;

use wadeshuler\subscriber\models\Subscriber;
use wadeshuler\subscriber\models\SubscriberSearch;

use wadeshuler\subscriber\models\SendEmailForm;
use wadeshuler\subscriber\models\SendSmsForm;

use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use ruskid\csvimporter\MultipleImportStrategy;

/**
 * Default controller for the `subscriber` module
 */
class ManageController extends Controller
{
    /**
     * @inheritdoc
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

    /**
     * Finds the Subscriber model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Subscriber the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ( $model = Subscriber::findOne($id) ) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Lists all Subscriber models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubscriberSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subscriber model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subscriber model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subscriber;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Subscriber model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Subscriber model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionImport()
    {
        $model = new ImportForm();

        if ( Yii::$app->request->post() )
        {
            $model->csvFile = UploadedFile::getInstance($model, 'csvFile');

            if ( $model->upload() )
            {

                $importTime = time();

                $importer = new CSVImporter;
                $importer->setData(new CSVReader([
                    'filename' => $model->csvFile->tempName,
                    'fgetcsvOptions' => [
                        'delimiter' => ','
                    ]
                ]));

                $numberRowsAffected = $importer->import(new MultipleImportStrategy([
                    'tableName' => Subscriber::tableName(),
                    'configs' => [
                        [
                            'attribute' => 'name',
                            'value' => function($line) {
                                $name = trim($line[1]);
                                return (isset($name) && !empty($name)) ? $name : null;
                            },
                            //'unique' => true, //Will filter and import unique values only. can by applied for 1+ attributes
                        ],
                        [
                            'attribute' => 'email_address',
                            'value' => function($line) {
                                $email = trim($line[2]);
                                return (isset($email) && !empty($email)) ? $email : null;
                            },
                            //'unique' => true, //Will filter and import unique values only. can by applied for 1+ attributes
                        ],
                        [
                            'attribute' => 'phone_number',
                            'value' => function($line) {
                                $phone = trim($line[3]);
                                if ( (isset($phone) && !empty($phone)) ) {
                                    $phone = str_replace('(', '', $phone);
                                    $phone = str_replace(')', '', $phone);
                                    $phone = str_replace('-', '', $phone);
                                    $phone = str_replace(' ', '', $phone);
                                    $phone = trim($phone);
                                    return $phone;
                                }
                                return null;
                            },
                            //'unique' => true, //Will filter and import unique values only. can by applied for 1+ attributes
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => function($line) use (&$importTime) {
                                return $importTime;
                            },
                        ],
                        [
                            'attribute' => 'updated_at',
                            'value' => function($line) use (&$importTime) {
                                return $importTime;
                            },
                        ],

                    ],
                ]));

                if ( $numberRowsAffected > 0 ) {
                    Yii::$app->session->setFlash('success', 'Imported ' . $numberRowsAffected . ' new contacts!');
                }
            }

        }

        return $this->render('import', [
            'model' => $model
        ]);
    }
}
