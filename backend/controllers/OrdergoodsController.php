<?php

namespace backend\controllers;

use backend\models\OrderGoods;
use frontend\models\Order;

class OrdergoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = OrderGoods::find()->all();
//        var_dump($models);exit;
        return $this->render('index',['models'=>$models]);
    }

    public function actionDel($id,$order_id){
    $model = OrderGoods::findOne(['id'=>$id]);
    $models = Order::findOne(['id'=>$order_id]);
    $model->delete();
    $models->delete();
    $this->redirect(['ordergoods/index']);
}
    public function actionEdit($id){
        $model = OrderGoods::findOne(['id'=>$id]);
        $model->status = 3;
        $model->save();
        $this->redirect(['ordergoods/index']);
    }

}
