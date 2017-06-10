<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_115628_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('文章分类名'),
            'intro'=>$this->text()->comment('简介'),
            'sort'=>$this->integer()->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('状态'),
            'is_help'=>$this->boolean()->defaultValue(false)->comment('是否帮助'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
