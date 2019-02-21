<?php

namespace wadeshuler\subscriber\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Error controller for the `subscriber` module
 */
class ErrorController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSmsNotConfigured()
    {
        return $this->render('sms-not-configured');
    }

    public function actionEmailNotConfigured()
    {
        return $this->render('email-not-configured');
    }
}
