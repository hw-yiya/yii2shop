<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m170610_032057_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree'=>$this->smallInteger()->comment('树id'),
            'lft'=>$this->smallInteger()->comment('左值'),
            'rgt'=>$this->smallInteger()->comment('右值'),
            'depth'=>$this->smallInteger()->comment('层级'),
            'name'=>$this->string(50)->comment('名称'),
            'parent_id'=>$this->smallInteger()->comment('上级分类id'),
            'intro'=>$this->text()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
