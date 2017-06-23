<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;

class Article_categoryController extends BackendController
{
    //列表
    public function actionIndex()
    {
        //定义方法
        $query = ArticleCategory::find();
        //找出总条数
        $total = $query->count();
        //每页显示2条
        $page = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $models = $query->offset($page->offset)->limit($page->limit)->all();
        //传出数据
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    //添加
    public function actionAdd()
    {
        $model = new ArticleCategory();
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
                return $this->redirect(['article_category/index']);
            } else {
                //打印错误
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        //根据id修改一条数据
        $model = ArticleCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        //模型接收表单提交的数据
        if ($request->isPost) {
            //表单数据
            $model->load($request->post());
            //验证并保存到数据表
            if ($model->validate()) {
                $model->save(false);
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转列表页
                return $this->redirect(['article_category/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //逻辑删除
    public function actionDel($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);
        return $this->redirect(['article_category/index']);
    }
}
