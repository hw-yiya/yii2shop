<?php
namespace backend\models;

use yii\base\Model;

class PasswdForm extends Model{
    public $oldPassword;//旧密码
    public $newPassword;//新密码
    public $rePassword;//确认新密码

    public function rules()
    {
        return [
            [['oldPassword','newPassword','rePassword'],'required'],
            //添加自定义验证
            ['oldPassword','validatePassword'],
            ['rePassword','compare','compareAttribute'=>'newPassword','message'=>'两次密码不一致'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword'=>'旧密码',
            'newPassword'=>'新密码',
            'rePassword'=>'确认密码',
        ];
    }
    public function validatePassword(){
        $passwordHash = \Yii::$app->user->identity->password;
        $password = $this->oldPassword;
        if(!\Yii::$app->security->validatePassword($password,$passwordHash)){
            $this->addError('oldPassword','旧密码不正确');
        }
    }
}