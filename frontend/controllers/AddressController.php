<?php

namespace frontend\controllers;

use chenkby\region\RegionAction;
use frontend\models\Address;
use frontend\models\Region;
use yii\web\NotFoundHttpException;

class AddressController extends \yii\web\Controller
{
    public $layout = 'goods';
    //添加收货地址
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['user/login']);
        }
        //得到当前会员的id
        $id = \Yii::$app->user->getId();
        $model = new Address();
        //根据登录会员查出其所有收货地址
        $addresses = Address::find()->where(['user_id'=>$id])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //拼接得到完整的收获地址
            $model->add_name = Region::getFullArea($model->province,$model->city,$model->district).' '.$model->add_detail;
            //根据登录身份得到当前的会员ID
            $model->user_id = $id;
            //执行保存收获地址信息
            $model->save(false);
            $this->refresh();
        }

        return $this->render('index',['model'=>$model,'addresses'=>$addresses]);
    }
    //重定义收货地址
    public function actionEdit($id){
        $model = Address::findOne(['id'=>$id]);
        $model = new Address();
        //根据登录会员查出其所有收货地址
        $addresses = Address::find()->where(['user_id'=>$id])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //拼接得到完整的收获地址
            $model->add_name = Region::getFullArea($model->province,$model->city,$model->district).' '.$model->add_detail;
            //执行保存收获地址信息
            $model->save(false);
            $this->refresh();
        }
        return $this->render('index',['model'=>$model,'addresses'=>$addresses]);
    }
    //删除收货地址
    public function actionDelete()
    {
        $id = \Yii::$app->request->post('id');
        $model = Address::findOne($id);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['get-region']=[
            'class'=>RegionAction::className(),
            'model'=>Region::className()
        ];
        return $actions;
    }

}
