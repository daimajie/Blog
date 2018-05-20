<?php

use yii\db\Migration;

/**
 * Class m180516_111902_alter_user_tbl
 */
class m180516_111902_alter_user_tbl extends Migration
{
    const TABLE_NAME = '{{%user}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'nickname', $this->string(18)->notNull()->defaultValue('')->comment('昵称'));
        $this->addColumn(self::TABLE_NAME, 'author', $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('作者'));
        $this->addColumn(self::TABLE_NAME, 'photo', $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->comment('头像'));
        $this->addColumn(self::TABLE_NAME,'qq_openid',$this->string(64)->notNull()->defaultValue('')->comment('qq账号'));
        $this->addColumn(self::TABLE_NAME,'wx_openid',$this->string(64)->notNull()->defaultValue('')->comment('微信账号'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'nickname');
        $this->dropColumn(self::TABLE_NAME, 'author');
        $this->dropColumn(self::TABLE_NAME, 'photo');
        $this->dropColumn(self::TABLE_NAME, 'qq_openid');
        $this->dropColumn(self::TABLE_NAME, 'wx_openid');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180516_111902_alter_user_tbl cannot be reverted.\n";

        return false;
    }
    */
}
