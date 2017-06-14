<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Articledetail;

class Article_detailController extends \yii\web\Controller
{
    //列表页
    public function actionIndex()
    {
        $models = Articledetail::find()->all();
        return $this->render('index',['models'=>$models]);
    }
    //添加
    public function actionAdd(){
        $model = new Articledetail();
        $request = \Yii::$app->request;
        //模型接收表单提交的数据
        if ($request->isPost) {
            //表单提交的数据
            $model->load($request->post());
            //验证并保存数据表
            if ($model->validate()) {
                $model->save(false);
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转列表页
                return $this->redirect(['article_detail/index']);
            } else {
                //打印错误
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id)
    {
        $model = Articledetail::findOne(['id' => $id]);
        $request = \Yii::$app->request;
        //模型接收表单提交的数据
        if ($request->isPost) {
            //表单提交的数据
            $model->load($request->post());
            //验证并保存数据表
            if ($model->validate()) {
                $model->save(false);
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转列表页
                return $this->redirect(['article_detail/index']);
            } else {
                //打印错误
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $model = Articledetail::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect('index');
    }

}
