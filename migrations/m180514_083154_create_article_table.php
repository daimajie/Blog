<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180514_083154_create_article_table extends Migration
{
    const TABLE_NAME = '{{%article}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->notnull()->comment('ID'),
            'title' => $this->string(255)->notnull()->defaultValue('')->comment('文章标题'),
            'brief' => $this->string(512)->notNull()->defaultValue('')->comment('文章简介'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('文章类型'),
            'draft' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('草稿'),
            'recycle' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('回收站'),
            'visited' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('阅读数'),
            'comment' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论数'),
            'likes' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('喜欢'),

            'topic_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属话题'),
            'content_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('内容'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('作者'),

            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),

        ],'engine=innodb charset=utf8');

        //文章标题
        $this->createIndex(
            'idx-article-title',
            self::TABLE_NAME,
            'title'
        );

        $this->createIndex(
            'idx-article-created_at',
            self::TABLE_NAME,
            'created_at'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-article-topic_id',
            self::TABLE_NAME,
            'topic_id',
            '{{%topic}}',
            'id',
            'no action'
        );
        $this->addForeignKey(
            'fk-article-content_id',
            self::TABLE_NAME,
            'content_id',
            '{{%content}}',
            'id',
            'no action'
        );

        $this->addForeignKey(
            'fk-article-user_id',
            self::TABLE_NAME,
            'user_id',
            '{{%user}}',
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
