<?php
namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\PasswdForm;
use yii\web\Controller;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class UserController extends Controller {
    public $layout = 'login';
    //用户注册
    public function actionRegister(){
        $model = new Member(['scenario'=>Member::SCENARIO_REGISTER]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->created_at = time();
            $model->updated_at = time();
            $model->save(false);
            \Yii::$app->session->setFlash('success','注册成功');
            return $this->redirect('login.html');
        }else{
//            var_dump($model->getErrors());exit;
        }
        return $this->render('register',['model'=>$model]);
    }
    //用户登录
    public function actionLogin(){
        $model = new LoginForm();
        $mod = new Member();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //var_dump($model);exit;
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->goBack(['index']);
            }
        return $this->render('login',['model'=>$model]);
    }
    //用户注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    public function actionSendSms()
    {
        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;
        }
        $code = rand(1000,9999);
        $result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        if($result){
            //保存当前验证码 session  mysql  redis  不能保存到cookie
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            echo 'success'.$code;
        }else{
            echo '发送失败';
        }
    }

    //测试短信插件
    public function actionSms()
    {
//        //安装插件 composer require flc/alidayu
//// 配置信息
//        $config = [
//            'app_key' => '24478397',
//            'app_secret' => '8383c9f61e7da1e793e30128644f837b',
//            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
//        ];
//
//
//        // 使用方法一
//        $client = new Client(new App($config));
//        $req = new AlibabaAliqinFcSmsNumSend;
//
//        $code = rand(1000, 9999);
//
//        $req->setRecNum('18882301701')//设置发给谁（手机号码）
//        ->setSmsParam([
//            'code' => $code//${code}
//        ])
//            ->setSmsFreeSignName('kieven网站')//设置短信签名，必须是已审核的签名
//            ->setSmsTemplateCode('SMS_71645165');//设置短信模板id，必须审核通过
//
//        $resp = $client->execute($req);
//        var_dump($resp);
//        var_dump($code);
        $code = rand(1000,9999);
        $result = \Yii::$app->sms->setNum(18882301701)->setParam(['code' => $code])->send();
        if($result){
            echo $code.'发送成功';
        }else{
            echo '发送失败';
        }
    }
    //邮箱
    public function actionMail()
    {
        //通过邮箱重设密码
        $result = \Yii::$app->mailer->compose()
            ->setFrom('hewen96@163.com')//谁的邮箱发出的邮件
            ->setTo('hewen96@163.com')//发给谁
            ->setSubject('六月感恩季，七牛献豪礼')//邮件的主题
            //->setTextBody('Plain text content')//邮件的内容text格式
            ->setHtmlBody('<b>注意: 每个 “mailer” 的扩展也有两个主要类别：“Mailer” 和 “Message”。 “Mailer” 总是知道类名和具体的 “Message”。 不要试图直接实例 “Message” 对象 - 而是始终使用 compose() 方法。</b>')//邮件的内容 html格式
            ->send();
        var_dump($result);
    }

}