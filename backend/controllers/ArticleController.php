<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\Articledetail;
use yii\data\Pagination;

class ArticleController extends BackendController
{
    //列表页
    public function actionIndex()
    {
        //查询出所有数据
        $query = Article::find();
        //得到数据总条数
        $total = $query->count();
        //实例化分页组件
        $page = new Pagination([
           'totalCount'=>$total,
            //每页指定显示几条数据
            'defaultPageSize'=>2,
        ]);
        //查询出数据库女所有数据
        $models = $query->offset($page->offset)->limit($page->limit)->all();
        //将数据分配到index页面
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    //添加
    public function actionAdd(){
        //实例化Article   Articledetail
        $model = new Article();
        $art =new Articledetail();
        //实例化提交方法
        $request = \Yii::$app->request;
        //判断是否是post调教方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $art->load($request->post());
            //验证数据
            if($model->validate() && $art->validate()){
                //当前时间
                $model->create_time = time();
                //保存数据
                if($model->save()){
                    $art->article_id=$model->id;
                    $art->save();
                }
                //提示信息   成功跳转index显示页面,失败则打印错误信息
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //将所需数据分配给add视图
        return $this->render('add',['model'=>$model,'art'=>$art]);
    }
    //修改
    public function actionEdit($id){
        //根据id查询出相关文章
        $model = Article::findOne(['id'=>$id]);
        $art =Articledetail::findOne(['article_id'=>$id]);
        //实例化提交方式
        $request = \Yii::$app->request;
        //是否是post调教方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $art->load($request->post());
            //验证数据
            if($model->validate() && $art->validate()){
                //当前时间
                $model->create_time = time();
                //保存数据
                if($model->save()){
                    $art->save(false);
                }
                //成功跳转显示页面,失败则打印错误信息
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('add',['model'=>$model,'art'=>$art]);
    }
    //逻辑删除  (修改状态)
    public function actionDel($id){
        //根据id修改相关文章的状态
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
    //查看
    public function actionSel($id){
        //根据id查询出相关文章详情
        $model = Article::findOne(['id'=>$id]);
        $model_detail = Articledetail::findOne(['article_id'=>$id]);
        return $this->render('sel',['model'=>$model,'model_detail'=>$model_detail]);
    }
}
