<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscriber`.
 */
class m170716_162043_create_subscriber_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%subscriber}}', [
            'id' => $this->primaryKey(),

            'name' => $this->string(100),
            'email_address' => $this->string()->unique(),
            'phone_number' => $this->string(20)->unique(),

            'ip_address' => $this->string(32),

            'email_marked_spam' => $this->boolean()->defaultValue(0),
            'email_bounced' => $this->boolean()->defaultValue(0),
            'email_unsubscribed' => $this->boolean()->defaultValue(0),
            'email_unsubscribed_at' => $this->integer(),

            'sms_bounced' => $this->boolean()->defaultValue(0),
            'sms_unsubscribed' => $this->boolean()->defaultValue(0),
            'sms_unsubscribed_at' => $this->integer(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%subscriber}}');
    }
}
