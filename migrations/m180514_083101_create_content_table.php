<?php

use yii\db\Migration;

/**
 * Handles the creation of table `content`.
 */
class m180514_083101_create_content_table extends Migration
{
    const TABLE_NAME = '{{%content}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('ID'),
            'content' => $this->text()->comment('文章内容'),
        ],'engine=innodb charset=utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->dropTable(self::TABLE_NAME);
    }
}
