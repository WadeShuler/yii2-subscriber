<?php

namespace wadeshuler\subscriber\models;

use Yii;
use yii\db\ActiveRecord;

use wadeshuler\subscriber\models\SmsQueue;

/**
 * This is the model class for table "{{%sms_campaign}}".
 *
 * @property int $id
 * @property string $message
 * @property int $created_at
 *
 * @property SmsQueue[] $smsQueues
 */
class SmsCampaign extends ActiveRecord
{

    public $testNumber;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms_campaign}}';
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
				],
			],
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'required'],
            [['message'], 'filter', 'filter' => 'trim'],
            [['message'], 'string', 'min' => 4],
            [['created_at'], 'integer'],
            [['testNumber'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'created_at' => 'Created At',
            'testNumber' => 'Test Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmsQueues()
    {
        return $this->hasMany(SmsQueue::className(), ['campaign_id' => 'id']);
    }
}
