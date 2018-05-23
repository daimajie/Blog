<?php

use yii\db\Migration;

/**
 * Class m180523_081320_alter_user_tbl
 */
class m180523_081320_alter_user_tbl extends Migration
{
    const TABLE_NAME = '{{%user}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,'total',$this->integer()->unsigned()->notNull()->defaultValue(0)->comment('写文章数目'));
        $this->addColumn(self::TABLE_NAME,'bg',$this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('背景图片'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME,'total');
        $this->dropColumn(self::TABLE_NAME,'bg');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180523_081320_alter_user_tbl cannot be reverted.\n";

        return false;
    }
    */
}
