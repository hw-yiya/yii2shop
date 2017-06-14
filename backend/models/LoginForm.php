<?php
namespace backend\models;

use backend\models\Admin;
use yii\base\Model;

class LoginForm extends Model{
    public $username;//用户名
    public $password;//密码
    public $rememberMe = true;//记住密码

    public function rules(){
        return [
            [['username','password'],'required'],
            //添加自定义验证
            ['username','validateUsername']
        ];
    }

    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
        ];
    }
    //自定义验证方法
    public function validateUsername(){
        $table = Admin::findOne(['username'=>$this->username]);
//        var_dump($table);exit;
        if($table){
//            if($this->password != $table->password){
            if(\Yii::$app->security->validatePassword($this->password,$table->password)){
                //账号密码正确登录
                \Yii::$app->user->login($table);

            }else{
                $this->addError('password','账号或密码不正确');
            }
        }else{
            //账号不存在
            $this->addError('username','账号或密码不正确');
        }
    }
}