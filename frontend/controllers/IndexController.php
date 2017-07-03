<?php

namespace frontend\controllers;

use backend\models\Goods_category;
use frontend\components\SphinxClient;

class IndexController extends \yii\web\Controller
{
    public $layout = 'index';
    public function actionIndex()
    {
        $categories = Goods_category::find()->where(['parent_id'=>0])->all();

                return $this->render('index',['categories'=>$categories]);
    }
//测试
    public function actionTest(){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        $info = '索尼电视';//需要搜索的词
        $res = $cl->Query($info, 'shopstore_search');//shopstore_search
//print_r($cl);
        var_dump($info);
    }

}
