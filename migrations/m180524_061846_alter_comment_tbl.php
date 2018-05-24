<?php

use yii\db\Migration;

/**
 * Class m180524_061846_alter_comment_tbl
 */
class m180524_061846_alter_comment_tbl extends Migration
{
    const TABLE_NAME = '{{%comment}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,'likes', $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('点赞次数')->after('reply'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME,'likes');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180524_061846_alter_comment_tbl cannot be reverted.\n";

        return false;
    }
    */
}
