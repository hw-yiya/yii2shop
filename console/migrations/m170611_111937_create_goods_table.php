<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170611_111937_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('商品名称'),
            'sn'=>$this->string()->comment('货号'),
            'logo'=>$this->string()->comment('商品logo'),
            'goods_category_id'=>$this->integer()->comment('商品分类'),
            'brand'=>$this->integer()->comment('品牌分类'),
            'market_price'=>$this->decimal(10,2)->comment('市场售价'),
            'shop_price'=>$this->decimal(10,2)->comment('商品价格'),
            'stock'=>$this->integer()->comment('库存'),
            'is_on_sale'=>$this->smallInteger(2)->comment('是否在售'),
            'status'=>$this->smallInteger(2)->comment('状态'),
            'sort'=>$this->Integer()->comment('排序'),
            'create_time'=>$this->integer()->comment('添加时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
