<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sms_queue`.
 */
class m190122_183712_create_sms_queue_table extends Migration
{
    private $tableName = '{{%sms_queue}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable($this->tableName, [
			'id' => $this->primaryKey(),
            'campaign_id' => $this->integer()->notNull(),
            'subscriber_id' => $this->integer()->notNull(),
            'data' => $this->text(),
            'attempts' => $this->integer()->defaultValue(0),
			'last_attempt_time' => $this->dateTime(),
            'time_to_send' => $this->dateTime()->notNull(),
			'sent_time' => $this->dateTime(),
            'created_at' => $this->dateTime()->notNull(),
		], $tableOptions);

        $this->addForeignKey('fk-sms_queue-sms_campaign', $this->tableName, 'campaign_id', '{{%sms_campaign}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-sms_queue-sms_subscriber', $this->tableName, 'subscriber_id', '{{%subscriber}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx-sms_queue-time_to_send', $this->tableName, 'time_to_send');
        $this->createIndex('idx-sms_queue-sent_time',    $this->tableName, 'sent_time');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sms_queue-sms_subscriber', $this->tableName);
        $this->dropForeignKey('fk-sms_queue-sms_campaign', $this->tableName);

        $this->dropIndex('idx-sms_queue-sent_time',    $this->tableName);
        $this->dropIndex('idx-sms_queue-time_to_send', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
