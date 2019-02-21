<?php

use yii\db\Migration;

/**
 * Handles the creation of table `email_campaign`.
 */
class m190122_183714_create_email_campaign_table extends Migration
{
    private $tableName = '{{%email_campaign}}';

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
            'subject' => $this->string(100)->notNull(),
            'pretext' => $this->string(99),
            'message' => $this->text()->notNull(),
            'batch_id' => $this->string(100),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
