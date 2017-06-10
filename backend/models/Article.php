<?php

namespace backend\models;

use backend\controllers\Article_categoryController;
use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    public function getArticle_category(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
    public function getArticledetail(){
        return $this->hasOne(Articledetail::className(),['article_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    //状态的选项
    static public $statusOptions=[-1=>'删除',0=>'隐藏',1=>'正常'];
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        //字段的验证规则
        return [
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'status'], 'integer'],
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
            'name' => '文章名',
            'intro' => '简介',
            'article_category_id' => '文章分类ID',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }
}
