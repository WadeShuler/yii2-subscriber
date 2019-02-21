<?php

namespace wadeshuler\subscriber;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;
use yii\db\Expression;

use wadeshuler\subscriber\models\EmailQueue;
use wadeshuler\subscriber\models\SmsQueue;

/**
 * subscriber module definition class
 */
class Module extends BaseModule implements BootstrapInterface
{
    public $defaultRoute = 'manage/index';
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'wadeshuler\subscriber\controllers';

    /**
	 * @var string the name of the database table to store the sms campaign.
	 */
	public $smsCampaignTable = '{{%sms_campaign}}';

    /**
	 * @var string the name of the database table to store the email campaign.
	 */
	public $emailCampaignTable = '{{%email_campaign}}';

	/**
	 * @var string the name of the database table to store the sms queue.
	 */
	public $smsQueueTable = '{{%sms_queue}}';

	/**
	 * @var string the name of the database table to store the email queue.
	 */
	public $emailQueueTable = '{{%email_queue}}';

    /**
     * @var int the number of sms messages to process per cron cycle.
     */
    public $smsBatchSize = 100;

    /**
     * @var int the number of emails to process per cron cycle.
     */
    public $emailBatchSize = 100;

    /**
     * @var int the number of times to retry sending an sms message.
     */
    public $smsMaxAttempts = 3;

    /**
     * @var int the number of times to retry sending an email.
     */
    public $emailMaxAttempts = 3;

    /**
     * @var bool whether or not to purge (delete) processed (old) sms messages from the queue table.
     */
    public $smsAutoPurge = false;

    /**
     * @var bool whether or not to purge (delete) processed (old) emails from the queue table.
     */
    public $emailAutoPurge = false;

    /**
     * @var string Full path to the HTML email template. Yii2 aliases supported.
     */
    public $emailTemplate;

    /**
     * @var string Your root domain URL for replacements (ie: https://example.com)
     */
    public $domainUrl;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'wadeshuler\subscriber\commands';
        }
    }

    public function processSmsQueue()
    {
        $this->smsMigrationCheck();

		$items = SmsQueue::find()
            ->where(['and', ['sent_time' => NULL], ['<', 'attempts', $this->smsMaxAttempts], ['<=', 'time_to_send', date('Y-m-d H:i:s')]])
            ->orderBy(['created_at' => SORT_ASC])
            ->limit($this->smsBatchSize)
        ;

        $totalCount = (int) $items->count();
        $successCount = 0;

		foreach ($items->each() as $item)
		{
			$attributes = ['attempts', 'last_attempt_time'];

			if ($this->sendSms($item->subscriber, $item->campaign)) {
			    $item->sent_time = new Expression('NOW()');
			    $attributes[] = 'sent_time';
                $successCount++;
			}

			$item->attempts++;
			$item->last_attempt_time = new Expression('NOW()');

			$item->updateAttributes($attributes);
		}

		// Purge messages now?
		if ($this->smsAutoPurge) {
			$this->purgeSmsQueue();
		}

		return ['total' => $totalCount, 'success' => $successCount];
    }

    public function processEmailQueue()
    {
        $this->emailMigrationCheck();

		$items = EmailQueue::find()
            ->where(['and', ['sent_time' => NULL], ['<', 'attempts', $this->emailMaxAttempts], ['<=', 'time_to_send', date('Y-m-d H:i:s')]])
            ->orderBy(['created_at' => SORT_ASC])
            ->limit($this->emailBatchSize)
        ;

        $totalCount = (int) $items->count();
        $successCount = 0;

		foreach ($items->each() as $item)
		{
			$attributes = ['attempts', 'last_attempt_time'];

			if ($this->sendEmail($item->subscriber, $item->campaign)) {
			    $item->sent_time = new Expression('NOW()');
			    $attributes[] = 'sent_time';
                $successCount++;
			}

			$item->attempts++;
			$item->last_attempt_time = new Expression('NOW()');

			$item->updateAttributes($attributes);
		}

		// Purge messages now?
		if ($this->emailAutoPurge) {
			$this->purgeEmailQueue();
		}

		return ['total' => $totalCount, 'success' => $successCount];
    }

    private function sendSms($subscriber, $campaign)
    {
        $to = $subscriber->phone_number;
        $message = $campaign->message;

        $domainUrl = ( isset($this->domainUrl) && ! empty($this->domainUrl) ) ? $this->domainUrl : '';
        $name = ( isset($subscriber->name) && ! empty($subscriber->name) ) ? $subscriber->name : '';
        $userId = ( isset($subscriber->id) && ! empty($subscriber->id) ) ? (string) $subscriber->id : '';
        $email = ( isset($subscriber->email_address) && ! empty($subscriber->email_address) ) ? $subscriber->email_address : '';

        $message = str_replace('-domainUrl-', $domainUrl, $message);
        $message = str_replace('-name-', $name, $message);
        $message = str_replace('-userId-', $userId, $message);
        $message = str_replace('-email-', $email, $message);

        return Yii::$app->sms->compose()
            ->setTo('+1' . $to)
            ->setMessage($message)
            ->send();
    }

    private function sendEmail($subscriber, $campaign)
    {
        $to = $subscriber->email_address;
        $subject = $campaign->subject;
        $pretext = ( isset($campaign->pretext) && ! empty($campaign->pretext) ) ? $campaign->pretext : '';
        $domainUrl = ( isset($this->domainUrl) && ! empty($this->domainUrl) ) ? $this->domainUrl : '';
        $name = ( isset($subscriber->name) && ! empty($subscriber->name) ) ? $subscriber->name : '';
        $userId = ( isset($subscriber->id) && ! empty($subscriber->id) ) ? (string) $subscriber->id : '';
        $email = ( isset($subscriber->email_address) && ! empty($subscriber->email_address) ) ? $subscriber->email_address : '';
        $message = $campaign->message;

        $html = '';

        if ( isset($this->emailTemplate) && ! empty($this->emailTemplate) )
        {
            $templateFile = Yii::getAlias($this->emailTemplate);

            if ( file_exists($templateFile) )
            {
                $html = file_get_contents($templateFile);
                $html = str_replace('-message-', $message, $html);
            }

        } else {

            $html = $message;

        }

        $html = str_replace('-pretext-', $pretext, $html);
        $html = str_replace('-domainUrl-', $domainUrl, $html);
        $html = str_replace('-name-', $name, $html);
        $html = str_replace('-userId-', $userId, $html);
        $html = str_replace('-email-', $email, $html);

        return Yii::$app->mailer->compose()
            ->setTo($to)
            ->setSubject($subject)
            ->setHtmlBody($html)
            ->send();
    }

	/**
	 * Deletes sent SMS messages from queue.
	 *
	 * @return int Number of rows deleted
	 */
	public function purgeSmsQueue()
	{
		return SmsQueue::deleteAll('sent_time IS NOT NULL');
	}

	/**
	 * Deletes sent Email messages from queue.
	 *
	 * @return int Number of rows deleted
	 */
	public function purgeEmailQueue()
	{
		return EmailQueue::deleteAll('sent_time IS NOT NULL');
	}

    private function smsMigrationCheck()
    {
        if (Yii::$app->db->getTableSchema($this->smsCampaignTable) == null) {
			throw new \yii\base\InvalidConfigException('"' . $this->smsCampaignTable . '" not found in database. Make sure the db migration is properly done and the table is created.');
		}

		if (Yii::$app->db->getTableSchema($this->smsQueueTable) == null) {
			throw new \yii\base\InvalidConfigException('"' . $this->smsQueueTable . '" not found in database. Make sure the db migration is properly done and the table is created.');
		}
    }

    private function emailMigrationCheck()
    {
        if (Yii::$app->db->getTableSchema($this->emailCampaignTable) == null) {
			throw new \yii\base\InvalidConfigException('"' . $this->emailCampaignTable . '" not found in database. Make sure the db migration is properly done and the table is created.');
		}

		if (Yii::$app->db->getTableSchema($this->emailQueueTable) == null) {
			throw new \yii\base\InvalidConfigException('"' . $this->emailQueueTable . '" not found in database. Make sure the db migration is properly done and the table is created.');
		}
    }
}
