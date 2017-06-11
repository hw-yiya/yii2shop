<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Articledetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Article::find();
        $total = $query->count();
        $page = new Pagination([
           'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $models = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    public function actionAdd(){
        $model = new Article();
        $art =new Articledetail();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $art->load($request->post());
            if($model->validate() && $art->validate()){
                $model->create_time = time();
                if($model->save()){
                    $art->article_id=$model->id;
                    $art->save();
                }

                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'art'=>$art]);
    }
    public function actionEdit($id){
        $model = Article::findOne(['id'=>$id]);
        $art =Articledetail::findOne(['article_id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $art->load($request->post());
            if($model->validate() && $art->validate()){
                $model->create_time = time();
                if($model->save()){
                    //$art->article_id=$model->id;
                    $art->save(false);
                }

                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'art'=>$art]);
    }
    public function actionDel($id){
        $model = Article::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);
        return $this->redirect(['article/index']);
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    public function actionSel($id){
        $model = Article::findOne(['id'=>$id]);
        $model_detail = Articledetail::findOne(['article_id'=>$id]);
        return $this->render('sel',['model'=>$model,'model_detail'=>$model_detail]);
    }
}
