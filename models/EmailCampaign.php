<?php

namespace wadeshuler\subscriber\models;

use Yii;
use yii\db\ActiveRecord;

use wadeshuler\subscriber\models\EmailQueue;

/**
 * This is the model class for table "{{%email_campaign}}".
 *
 * @property int $id
 * @property string $subject
 * @property string $pretext
 * @property string $message
 * @property string $batch_id
 * @property int $created_at
 *
 * @property EmailQueue[] $emailQueues
 */
class EmailCampaign extends ActiveRecord
{

    public $testEmail;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%email_campaign}}';
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
             [['created_at'], 'integer'],
             ['subject', 'filter', 'filter' => 'trim'],
             ['subject', 'required'],
             ['subject', 'string', 'min' => 4, 'max' => 100],

             ['message', 'filter', 'filter' => 'trim'],
             ['message', 'required'],
             ['message', 'string', 'min' => 4],

             ['pretext', 'string', 'min' => 10, 'max' => 99],
             [['batch_id'], 'safe'],

             [['testEmail'], 'email'],
         ];
     }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Subject',
            'pretext' => 'Pretext',
            'message' => 'Message',
            'batch_id' => 'Batch ID',
            'created_at' => 'Created At',
            'testEmail' => 'Test Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmailQueues()
    {
        return $this->hasMany(EmailQueue::className(), ['campaign_id' => 'id']);
    }
}
