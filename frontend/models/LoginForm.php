<?php
namespace frontend\models;

use frontend\models\Member;
use yii\base\Model;

class LoginForm extends Model{
    public $username;//用户名
    public $password;//密码
    public $rememberMe = true;//记住密码
    public $code;//验证码

    public function rules(){
        return [
            [['username','password'],'required'],
            //添加自定义验证
            ['username','validateUsername'],
            ['code','captcha']
        ];
    }

    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记住密码',
            'code'=>'验证码',
        ];
    }
    //自定义验证方法
    public function validateUsername(){
        $model = Member::findOne(['username'=>$this->username]);
        if($model){


            if(\Yii::$app->security->validatePassword($this->password,$model->password_hash)){
                //账号密码正确登录
                $model-> generateAuthKey();
                $model->last_login_time = time();
                $model->last_login_ip = \Yii::$app->request->userIP;
                //var_dump($model);exit;
                $model->save(false);
                \Yii::$app->user->login($model,$this->rememberMe ? 3600 * 24 * 30 : 0);

            }else{
                $this->addError('password','账号或密码不正确');
            }
        }else{
            //账号不存在
            $this->addError('username','账号或密码不正确');
        }
    }
}