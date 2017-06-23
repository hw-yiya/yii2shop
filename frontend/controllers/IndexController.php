<?php

namespace frontend\controllers;

use backend\models\Goods_category;

class IndexController extends \yii\web\Controller
{
    public $layout = 'index';
    public function actionIndex()
    {
        $categories = Goods_category::find()->where(['parent_id'=>0])->all();

        return $this->render('index',['categories'=>$categories]);
    }

}
