<?php

use yii\db\Migration;

/**
 * Class m180519_144344_create_friend_tbl
 */
class m180519_144344_create_friend_tbl extends Migration
{
    const TABLE_NAME = '{{%friend}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('ID'),
            'name' => $this->string(32)->notNull()->defaultValue('')->comment('站点名称'),
            'url' => $this->string(128)->notNull()->defaultValue('')->comment('站点地址'),
            'sort' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('排序'),
        ],'engine=innodb charset=utf8');

        $this->createIndex(
            'idx-friend-sort',
            self::TABLE_NAME,
            'sort'
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
        echo "m180519_144344_create_friend_tbl cannot be reverted.\n";

        return false;
    }
    */
}
