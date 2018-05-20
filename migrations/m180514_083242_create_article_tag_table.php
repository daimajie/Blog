<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_tag`.
 */
class m180514_083242_create_article_tag_table extends Migration
{
    const TABLE_NAME = '{{%article_tag}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'tag_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('标签'),
            'article_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章'),
        ],'engine=innodb charset=utf8');

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-article-tag-tag_id',
            self::TABLE_NAME,
            'tag_id',
            '{{%tag}}',
            'id',
            'no action'
        );

        $this->addForeignKey(
            'fk-article-tag-article_id',
            self::TABLE_NAME,
            'article_id',
            '{{%article}}',
            'id',
            'no action'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->dropTable(self::TABLE_NAME);
    }
}
