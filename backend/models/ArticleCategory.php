<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 * @property integer $is_help
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    static public $statusOptions=[-1=>'删除',0=>'隐藏',1=>'正常'];
    static public $is_helpOptions=[1=>'是',2=>'否'];
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        //字段验证规则
        return [
            [['intro'], 'string'],
            [['sort', 'status', 'is_help'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        //设置属性的标签名称
        return [
            'id' => 'ID',
            'name' => '文章分类名',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'is_help' => '是否帮助',
        ];
    }
}
