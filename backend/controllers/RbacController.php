<?php
namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller{
    //添加权限
    public function actionAddPermission(){
        $model = new PermissionForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addPermission()){
                \Yii::$app->session->setFlash('success','添加权限成功');
                return $this->redirect('permission-index');
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionPermissionIndex(){
        $models = \Yii::$app->authManager->getPermissions();

        return $this->render('permission-index',['models'=>$models]);
    }
    //修改权限
    public function actionEditPermission($name){
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('该权限不存在');
        }
        $model = new PermissionForm();
        //把需要修改的权限赋值给表单模型
        $model->loadData($permission);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updatePermission($name)){
                \Yii::$app->session->setFlash('success','修改权限成功');
                return $this->redirect('permission-index');
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //删除权限
    public function actionDelPermission($name){
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('该权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除权限成功');
        return $this->redirect('permission-index');
    }
    //添加角色
    public function actionAddRole(){
        $model = new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addRole()){
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect('role-index');
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    //角色列表
    public function actionRoleIndex(){
        $models = \Yii::$app->authManager->getRoles();

        return $this->render('role-index',['models'=>$models]);
    }
    //修改角色
    public function actionEditRole($name){
        $role = \Yii::$app->authManager->getRole($name);
        if($role->name == null){
            throw new NotFoundHttpException('该角色不存在');
        }
        $model = new RoleForm();
        $model->loadData($role);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updateRole($name)){
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect('role-index');
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    public function actionDelRole($name){
        $role = \Yii::$app->authManager->getRole($name);
        if($role == null){
            throw new NotFoundHttpException('角色不存在');
        }
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('success','角色删除成功');
        return $this->redirect(['role-index']);
    }
}