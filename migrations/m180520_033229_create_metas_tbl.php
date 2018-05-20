<?php

use yii\db\Migration;

/**
 * Class m180520_033229_create_metas_tbl
 */
class m180520_033229_create_metas_tbl extends Migration
{
    const TABLE_NAME = '{{%metas}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('ID'),
            'sitename' => $this->string(32)->notNull()->defaultValue('')->comment('站点名称'),
            'keywords' => $this->string(225)->notNull()->defaultValue('')->comment('关键字'),
            'description' => $this->string(512)->notNull()->defaultValue('')->comment('站点描述'),
            'aboutme' => $this->string(512)->notNull()->defaultValue('')->comment('关于我'),
        ],'engine=innodb charset=utf8');

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
        echo "m180520_033229_create_metas_tbl cannot be reverted.\n";

        return false;
    }
    */
}
