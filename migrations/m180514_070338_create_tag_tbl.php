<?php

use yii\db\Migration;

/**
 * Class m180514_070338_create_tag_tbl
 */
class m180514_070338_create_tag_tbl extends Migration
{
    const TABLE_NAME = '{{%tag}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('ID'),
            'name' => $this->string(15)->notNull()->defaultValue('')->comment('标签名'),
            'topic_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属话题'),
        ],'engine=innodb charset=utf8');

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-tag-topic_id',
            self::TABLE_NAME,
            'topic_id',
            '{{%topic}}',
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

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180514_070338_create_tag_tbl cannot be reverted.\n";

        return false;
    }
    */
}
