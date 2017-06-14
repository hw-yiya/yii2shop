<?php

namespace backend\controllers;

use backend\models\Goods_category;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class Goods_categoryController extends \yii\web\Controller
{
    //列表页喊下拉列表
    public function actionIndex()
    {
        //查询出所有分类
        $models = Goods_category::find()->orderBy('tree,lft')->all();



        return $this->render('index',['models'=>$models]);
    }
    //添加
    public function actionAdd(){
        $model = new Goods_category();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是添加一级分类（parent_id是否为0）
            if($model->parent_id){
                //添加非一级分类
                $parent = Goods_category::findOne(['id'=>$model->parent_id]);//获取上一级分类
                $model->prependTo($parent);//添加到上一级分类下面
            }else{
                //添加一级分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods_category/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->asArray()->all());


        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    //修改
    public function actionEdit($id){
        $model = Goods_category::findOne(['id'=>$id]);
            if($model==null){
                throw new NotFoundHttpException('分类不存在');
            }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是添加一级分类（parent_id是否为0）
            if($model->parent_id){
                //添加非一级分类
                $parent = Goods_category::findOne(['id'=>$model->parent_id]);//获取上一级分类
                $model->prependTo($parent);//添加到上一级分类下面
            }else{
                if($model->getOldAttribute('parent_id')==0){
                    $model->save();
                }else{
                    //添加一级分类
                    $model->makeRoot();
                }
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods_category/index']);
        }
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->asArray()->all());


        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    public function actionZtree(){
        $categories = Goods_category::find()->asArray()->all();
       return $this->renderPartial('ztree',['categories'=>$categories]);//不加载布局文件
    }

}
