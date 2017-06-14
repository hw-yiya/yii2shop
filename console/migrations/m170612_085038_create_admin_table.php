<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170612_085038_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username'=>$this->string()->comment('用户名'),
            'password'=>$this->string(110)->comment('密码'),
            'last_time'=>$this->integer()->comment('最后登录时间'),
            'last_ip'=>$this->string()->comment('最后登录IP'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
