<?php

use yii\db\Migration;

class m260311_122514_urls_logs extends Migration
{
    public function safeUp()
    {
        $this->createTable('urls', [
            'id' => $this->primaryKey(),
            'url' => $this->text(),
            'short' => $this->string(6),
            'count' => $this->integer()->defaultValue(0),
        ]);

        $this->createTable('logs', [
            'id' => $this->primaryKey(),
            'url_id' => $this->integer(),
            'ip' => $this->string(45),
            'visited_at' => $this->datetime()
        ]);

        $this->createIndex('short_index', 'urls', 'short');
        $this->addForeignKey('logs_url_id', 'logs', 'url_id', 'urls', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropIndex('short_index', 'urls');
        $this->dropForeignKey('logs_url_id', 'logs');
        $this->dropTable('logs');
        $this->dropTable('urls');
    }
}