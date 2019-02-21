<?php
/**
 * Subscriber Command Controller
 */

namespace wadeshuler\subscriber\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * This command processes the sms queue
 */
class CronController extends Controller
{

    public $defaultAction = 'help';

    private function getModule()
    {
        return Yii::$app->getModule('subscriber');
    }

    public function actionHelp()
    {
        $this->stdout("Sorry, there is no help.\n");
    }

    /**
     * This command runs the cron jobs
     */
    public function actionRun()
    {
        $this->processEmailQueue();
        $this->processSmsQueue();
    }

    private function processSmsQueue()
    {
        $this->stdout("Processing the sms queue.\n");
        $this->stdout("SMS batch size: " . $this->getModule()->smsBatchSize . "\n");
        $this->stdout("\n");

        $result = $this->getModule()->processSmsQueue();

        if ( is_array($result) && isset($result['total'], $result['success']) )
        {

            if ( $result['total'] === 0 ) {
                $this->stdout("No SMS messages to process.\n");
            } else {
                $this->stdout("SMS messages processed: " . $result['success'] . "/" . $result['total'] . "\n");
            }

        } else {

            $this->stdout("Error processing the SMS queue.\n");

        }

        $this->stdout("\n");

    }

    private function processEmailQueue()
    {
        $this->stdout("Processing the email queue.\n");
        $this->stdout("Email batch size: " . $this->getModule()->emailBatchSize . "\n");
        $this->stdout("\n");

        $result = $this->getModule()->processEmailQueue();

        if ( is_array($result) && isset($result['total'], $result['success']) )
        {

            if ( $result['total'] === 0 ) {
                $this->stdout("No Email messages to process.\n");
            } else {
                $this->stdout("Email messages processed: " . $result['success'] . "/" . $result['total'] . "\n");
            }

        } else {

            $this->stdout("Error processing the Email queue.\n");

        }

        $this->stdout("\n");
    }
}
