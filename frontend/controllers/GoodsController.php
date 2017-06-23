<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\Goods_category;
use backend\models\GoodsImg;

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
}
