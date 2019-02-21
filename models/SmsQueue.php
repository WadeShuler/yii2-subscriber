<?php

namespace wadeshuler\subscriber\models;

use Yii;
use yii\db\ActiveRecord;

use wadeshuler\subscriber\models\SmsCampaign;
use wadeshuler\subscriber\models\Subscriber;

/**
 * This is the model class for table "{{%sms_queue}}".
 *
 * @property int $id
 * @property int $campaign_id
 * @property int $subscriber_id
 * @property string $data
 * @property int $attempts
 * @property string $last_attempt_time
 * @property string $time_to_send
 * @property string $sent_time
 * @property string $created_at
 *
 * @property SmsCampaign $campaign
 * @property Subscriber $subscriber
 */
class SmsQueue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms_queue}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => 'yii\behaviors\TimestampBehavior',
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
					ActiveRecord::EVENT_BEFORE_UPDATE => ['last_attempt_time'],
				],
				'value' => new \yii\db\Expression('NOW()'),
			],
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'subscriber_id', 'time_to_send', 'created_at'], 'required'],
            [['campaign_id', 'subscriber_id', 'attempts'], 'integer'],
            [['data'], 'string'],
            [['last_attempt_time', 'time_to_send', 'sent_time', 'created_at'], 'safe'],
            [['campaign_id'], 'exist', 'skipOnError' => true, 'targetClass' => SmsCampaign::className(), 'targetAttribute' => ['campaign_id' => 'id']],
            [['subscriber_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscriber::className(), 'targetAttribute' => ['subscriber_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campaign_id' => 'Campaign ID',
            'subscriber_id' => 'Subscriber ID',
            'data' => 'Data',
            'attempts' => 'Attempts',
            'last_attempt_time' => 'Last Attempt Time',
            'time_to_send' => 'Time To Send',
            'sent_time' => 'Sent Time',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(SmsCampaign::className(), ['id' => 'campaign_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriber()
    {
        return $this->hasOne(Subscriber::className(), ['id' => 'subscriber_id']);
    }
}
