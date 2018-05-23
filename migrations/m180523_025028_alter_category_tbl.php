<?php

use yii\db\Migration;

/**
 * Class m180523_025028_alter_category_tbl
 */
class m180523_025028_alter_category_tbl extends Migration
{
    const TABLE_NAME = '{{%category}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME,'pic',$this->string(120)->notNull()->defaultValue('')->comment('分类图片'));
        $this->addColumn(self::TABLE_NAME,'created_at',$this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'));


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME,'pic');
        $this->dropColumn(self::TABLE_NAME,'created_at');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180523_025028_alter_category_tbl cannot be reverted.\n";

        return false;
    }
    */
}
