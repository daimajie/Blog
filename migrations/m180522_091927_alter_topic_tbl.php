<?php

use yii\db\Migration;

/**
 * Class m180522_091927_alter_topic_tbl
 */
class m180522_091927_alter_topic_tbl extends Migration
{
    const TABLE_NAME = '{{%topic}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,'count',$this->integer()->unsigned()->notNull()->defaultValue(0)->comment('收录文章'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME,'count');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180522_091927_alter_topic_tbl cannot be reverted.\n";

        return false;
    }
    */
}
