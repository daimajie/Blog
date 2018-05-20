<?php

use yii\db\Migration;

/**
 * Class m180513_044717_create_category_tbl
 */
class m180513_044717_create_category_tbl extends Migration
{
    const TBL_NAME = '{{%category}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME, [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'name' => $this->string(15)->notNull()->defaultValue('')->comment('分类名'),
            'desc' => $this->string(255)->notNull()->defaultValue('')->comment('简述'),
        ], 'engine=innodb charset=utf8');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TBL_NAME);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180513_044717_create_category_tbl cannot be reverted.\n";

        return false;
    }
    */
}
