<?php
namespace backend\components;

use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{
    public function beforeAction($action)
    {
        $user = \Yii::$app->user;
        if(!$user->can($action->uniqueId)){
            //用户没有登录，提醒未登录
            if($user->isGuest){
                return $action->controller->redirect($user->loginUrl);
            }
            throw new HttpException(403,'对不起你没有权限,请尽快联系超级管理员');
            return false;
        }
        return parent::beforeAction($action);
    }
}