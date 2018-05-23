<?php

use yii\db\Migration;

/**
 * Class m180523_113710_alter_article_tbl
 */
class m180523_113710_alter_article_tbl extends Migration
{
    const TABLE_NAME = '{{%article}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,'collect',$this->integer()->unsigned()->notNull()->defaultValue(0)->comment('收藏数目')->after('likes'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME,'collect');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180523_113710_alter_article_tbl cannot be reverted.\n";

        return false;
    }
    */
}
