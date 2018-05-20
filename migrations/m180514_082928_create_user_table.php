<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180514_082928_create_user_table extends Migration
{
    const TABLE_NAME = '{{%user}}';
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey()->unsigned()->notNull()->comment('ID'),
            'username' => $this->string(15)->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->comment('auth_key'),
            'password_hash' => $this->string()->notNull()->comment('密码'),
            'password_reset_token' => $this->string()->unique()->comment('密码重置token'),
            'email' => $this->string()->notNull()->unique()->comment('email'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10)->comment('状态'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->unsigned()->defaultValue(0)->comment('修改时间'),
        ], 'engine=innodb charset=utf8');


    }


    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
