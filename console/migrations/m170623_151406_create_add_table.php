<?php

use yii\db\Migration;

/**
 * Handles the creation of table `add`.
 */
class m170623_151406_create_add_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('add', [
            'id' => $this->primaryKey(),
//goods_id	int	商品id
            'goods_id'=>$this->integer(),
//amount	int	商品数量
            'amount'=>$this->integer(),
//member_id	int	用户id
            'member_id'=>$this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('add');
    }
}
