<?php

use yii\db\Migration;

/**
 * Class m180519_140349_create_notebook_tbl
 */
class m180519_140349_create_notebook_tbl extends Migration
{
    const TABLE_NAME = '{{%notebook}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('ID'),
            'content' => $this->text()->notNull()->comment('内容'),
            'user_id' =>$this->integer()->unsigned()->notNull()->defaultValue(0)->comment('署名'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间')
        ],'engine=innodb charset=utf8');

        $this->addForeignKey(
            'fk-notebook-user_id',
            self::TABLE_NAME,
            'user_id',
            '{{%user}}',
            'id',
            'no action'
        );

        $this->createIndex(
            'idx-notebook-created_at',
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
        echo "m180519_140349_create_notebook_tbl cannot be reverted.\n";

        return false;
    }
    */
}
