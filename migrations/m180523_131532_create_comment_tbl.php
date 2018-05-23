<?php

use yii\db\Migration;

/**
 * Class m180523_131532_create_comment_tbl
 */
class m180523_131532_create_comment_tbl extends Migration
{
    const TABLE_NAME = '{{%comment}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('ID'),
            'content' => $this->text()->notNull()->comment('内容'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('用户id'),
            'article_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章id'),
            'reply' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('是否是回复'),
            'comment_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论id'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
        ],'engine=innodb charset=utf8');

        //创建索引
        $this->addForeignKey(
            'fk-comment-user_id',
            self::TABLE_NAME,
            'user_id',
            '{{%user}}',
            'id',
            'no action'
        );
        //创建索引
        $this->addForeignKey(
            'fk-comment-article_id',
            self::TABLE_NAME,
            'article_id',
            '{{%article}}',
            'id',
            'no action'
        );
        $this->createIndex(
            'idx-comment_id',
            self::TABLE_NAME,
            'comment_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180523_131532_create_comment_tbl cannot be reverted.\n";

        return false;
    }
    */
}
