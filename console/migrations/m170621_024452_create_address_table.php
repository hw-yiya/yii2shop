<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_024452_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(11)->comment('用户'),
            'name'=>$this->string(30)->comment('收货人'),
            'add_name'=>$this->text()->notNull()->comment('收货地址'),
            'tel'=>$this->integer(11)->notNull()->comment('手机号码'),
            'is_default'=>$this->boolean()->defaultValue(false)->comment('是否默认')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
