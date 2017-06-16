<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class UserForm extends Model{
    public $roles=[];

    public function rules()
    {
        return [
            ['roles','safe']
        ];
    }
    public function attributeLabels()
    {
        return [
            'roles'=>'角色',
        ];
    }
    //获取所有角色
    public static function getRoleOption(){
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(), 'name', 'description');
    }
    public function assignRole($id){
        $authManager = \Yii::$app->authManager;
        if(Admin::findOne($id)){
            $authManager->revokeAll($id);
            $roles = $this->roles;
            if($roles){
                foreach ($roles as $roleName){
                    $authManager->assign($authManager->getRole($roleName),$id);
                }
            }
            return true;
        }else{
            throw new NotFoundHttpException('该用户不存在');
        }
    }
    //赋值
    public function loadData($id){
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id);
        foreach ($roles as $role){
            $this->roles[] = $role->name;
        }
    }
}