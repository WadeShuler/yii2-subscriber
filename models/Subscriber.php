<?php

namespace wadeshuler\subscriber\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "subscriber".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $email_address
 * @property string  $phone_number
 * @property string  $ip_address
 * @property integer $email_marked_spam
 * @property integer $email_bounced
 * @property integer $email_unsubscribed
 * @property integer $email_unsubscribed_at
 * @property integer $sms_bounced
 * @property integer $sms_unsubscribed
 * @property integer $sms_unsubscribed_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class Subscriber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscriber}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_marked_spam', 'email_bounced', 'email_unsubscribed', 'email_unsubscribed_at', 'sms_bounced', 'sms_unsubscribed', 'sms_unsubscribed_at', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['email_address'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 20],
            [['ip_address'], 'string', 'max' => 32],

            [['phone_number', 'email_address'], 'default', 'value' => null],

            [['email_address'], 'unique'],
            [['phone_number'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email_address' => 'Email Address',
            'phone_number' => 'Phone Number',
            'ip_address' => 'IP Address',
            'email_marked_spam' => 'Email Marked Spam',
            'email_bounced' => 'Email Bounced',
            'email_unsubscribed' => 'Email Unsubscribed',
            'email_unsubscribed_at' => 'Email Unsubscribed At',
            'sms_bounced' => 'SMS Bounced',
            'sms_unsubscribed' => 'SMS Unsubscribed',
            'sms_unsubscribed_at' => 'SMS Unsubscribed At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
