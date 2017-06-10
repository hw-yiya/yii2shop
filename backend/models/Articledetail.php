<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_detail".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $content
 */
class Articledetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function getArticle(){
        return $this->hasOne(Article::className(),['id'=>'article_id']);
    }
    public static function tableName()
    {
        return 'article_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => '文章id',
            'content' => '内容',
        ];
    }
}
