<?php

use yii\db\Migration;

/**
 * Class m180523_084702_create_likes_collect_tbl
 */
class m180523_084702_create_likes_collect_tbl extends Migration
{
    const TABLE_NAME = '{{%likes_collect}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('ID'),
            'user_id' =>$this->integer()->unsigned()->notNull()->defaultValue(0)->comment('用户id'),
            'article_id' =>$this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章id'),
            'type' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('0为喜欢点赞,1为收藏'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
        ],'engine=innodb charset=utf8');

        //创建索引
        $this->addForeignKey(
            'fk-user-user_id',
            self::TABLE_NAME,
            'user_id',
            '{{%user}}',
            'id',
            'no action'
        );
        $this->addForeignKey(
            'fk-article-article_id',
            self::TABLE_NAME,
            'article_id',
            '{{%article}}',
            'id',
            'no action'
        );
        $this->createIndex(
            'idx-likes_collect-created_at',
            self::TABLE_NAME,
            'created_at'
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
        echo "m180523_084702_create_likes_collect_tbl cannot be reverted.\n";

        return false;
    }
    */
}
