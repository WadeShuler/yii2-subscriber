<?php

use yii\db\Migration;

/**
 * Handles the creation of table `email_queue`.
 */
class m190122_183716_create_email_queue_table extends Migration
{
    private $tableName = '{{%email_queue}}';

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

        $this->addForeignKey('fk-email_queue-email_campaign', $this->tableName, 'campaign_id', '{{%email_campaign}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-email_queue-email_subscriber', $this->tableName, 'subscriber_id', '{{%subscriber}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx-email_queue-time_to_send', $this->tableName, 'time_to_send');
        $this->createIndex('idx-email_queue-sent_time',    $this->tableName, 'sent_time');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-email_queue-email_subscriber', $this->tableName);
        $this->dropForeignKey('fk-email_queue-email_campaign', $this->tableName);

        $this->dropIndex('idx-email_queue-sent_time',    $this->tableName);
        $this->dropIndex('idx-email_queue-time_to_send', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
