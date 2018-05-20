<?php

use yii\db\Migration;

/**
 * Class m180514_022318_create_topic_tbl
 */
class m180514_022318_create_topic_tbl extends Migration
{
    const TABLE_NAME = '{{%topic}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'name' => $this->string(15)->notNull()->defaultValue('')->comment('话题名'),
            'desc' => $this->string(255)->notNull()->defaultValue('')->comment('简述'),
            'category_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属分类'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
        ], 'engine=innodb charset=utf8');


        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-topic-category_id',
            self::TABLE_NAME,
            'category_id',
            '{{%category}}',
            'id',
            'no action'
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
        echo "m180514_022318_create_topic_tbl cannot be reverted.\n";

        return false;
    }
    */
}
