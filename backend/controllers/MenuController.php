<?php

namespace backend\controllers;

use backend\models\Menu;

class MenuController extends BackendController
{
    public function actionIndex()
    {
        $models = Menu::find()->all();
        return $this->render('index',['models'=>$models]);
    }
    public function actionAdd(){
        $model = new Menu();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            //跳转列表页
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model = Menu::findOne($id);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            //跳转列表页
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $model = Menu::findOne($id);
        $model->delete();
        return $this->redirect(['menu/index']);
    }
}
