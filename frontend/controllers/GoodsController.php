<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\Goods_category;
use backend\models\GoodsImg;
use frontend\components\SphinxClient;
use frontend\models\Cart;
use frontend\models\Order;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class GoodsController extends \yii\web\Controller
{
    public $layout = 'goods';
    //商品列表
    public function actionList($id)
    {
        $goods = Goods::find()->all();
        $categories = Goods_category::findOne($id);

        return $this->render('list',['goods'=>$goods,'categories'=>$categories]);
    }
    //收获地址
    public function actionAddress()
    {
        return $this->render('address');
    }
    //商品详情
    public function actionDetail($id)
    {
        $goods = Goods::findOne(['id'=>$id]);//商品数据
        $goods_cate = Goods_category::findOne($goods->goods_category_id);
        $goodsimgs = GoodsImg::find()->where(['goods_id'=>$id])->all();
        //var_dump($goodsimgs);exit;
        return $this->render('detail',['goods'=>$goods,'goodsimgs'=>$goodsimgs,'goods_cate'=>$goods_cate]);
    }
    //搜索
    public function actionSearch()
    {
//        $goods = Goods::find()->all();
        $keyword = \Yii::$app->request->get('keyword');
        $cl = new SphinxClient();
        $cl->SetServer('127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout(10);
        $cl->SetArrayResult(true);
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode(SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        $res = $cl->Query($keyword, 'goods');//shopstore_search
        if(isset($res['matches'])){
            $ids = ArrayHelper::map($res['matches'],'id','id');
            $goods = Goods::find()->where(['in','id',$ids])->all();
        }else{
            $goods = Goods::find()->all();
        }
//        var_dump($res);exit;
        $keywords = array_keys($res['words']);
        $options = array(
            'before_match' => '<span style="color:red;">',
            'after_match' => '</span>',
            'chunk_separator' => '...',
            'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
        );
//关键字高亮
        //        var_dump($models);exit;
        foreach ($goods as $index => $item) {
            $name = $cl->BuildExcerpts([$item->name], 'goods', implode(',', $keywords), $options); //使用的索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
            $goods[$index]->name = $name[0];
//            var_dump($name);
        }
//        var_dump($item);
//        exit;
        return $this->render('list',['goods'=>$goods]);
    }
}
