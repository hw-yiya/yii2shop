<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Admin;
use backend\models\PasswdForm;
use backend\models\LoginForm;
use backend\models\UserForm;

class AdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>['add','edit','del','rbac'],
            ]
        ];
    }
    //列表页
    public function actionIndex()
    {
        $models = Admin::find()->all();
        return $this->render('index',['models'=>$models]);
    }
    //添加
    public function actionAdd()
    {
        $model = new Admin();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //var_dump($model);exit;
            //验证
            if($model->validate()){
                $model->save(false);
                //var_dump($model->getErrors());exit;
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/login']);
            }else{}
            var_dump($model->getErrors());exit;
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model = Admin::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //var_dump($model);exit;
            //验证
            if($model->validate()){
                $model->save(false);
                //var_dump($model->getErrors());exit;
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }else{}
            var_dump($model->getErrors());exit;
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $model = Admin::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect('index');
    }
    //登录
    public function actionLogin(){
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['site/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    //修改密码
    public function actionPas(){
        $model = new PasswdForm();
        $request = \Yii::$app->request;
        if($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $adm = \Yii::$app->user->identity;
                $adm->password = $model->newPassword;
                if($adm->save(false)){
                    \Yii::$app->session->setFlash('success','密码修改成功');
                    return $this->redirect(['admin/index']);
                }else{
                    var_dump($adm->getErrors());exit;
                }
            }
        }
        return $this->render('pas',['model'=>$model]);
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['admin/login']);
    }
    //设置指定权限角色
    public function actionRbac($id){
        $model = new UserForm();
        $name = Admin::findOne($id);
        $model->loadData($id);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->assignRole($id)){
                \Yii::$app->session->setFlash('success','角色设置成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('rbac',['model'=>$model]);
    }
}
