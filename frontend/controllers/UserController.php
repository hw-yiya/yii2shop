<?php
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\PasswdForm;
use yii\db\Exception;
use yii\web\Controller;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

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
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie != null){
                foreach (unserialize($cookie) as $key=>$cart){
                    $model = new Cart();
                    $model->member_id = \Yii::$app->user->id;
                    $model->goods_id = $key;
                    $model->amount = $cart;
                    $model->save(false);
                }
            }
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

    //添加到购物车
    public function actionAddCart()
    {
        //接收数据
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods =  Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }
        //实例化
        $cart = new Cart();
        //判断是否登录，未登录操作cookie,登录操作数据库
        if(\Yii::$app->user->isGuest){
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookies中没有购物车的数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            //将商品的id和数量存到cookie中
            $cookies = \Yii::$app->response->cookies;
            //检查购物车中是否有该商品，有，数量累加
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            //$cart = [$goods_id=>$amount];
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //已登录，操作数据库
            //得到登录用户的id
            $id = \Yii::$app->user->getId();
            //先获取数据库中的购物车数据
            $model = Cart::findOne(['member_id'=>$id]);
            //var_dump($goods_id);exit;
            if($model==null){
                //表示数据库还么有该会员的购物车信息，直接新加一条记录
                $cart->add($goods_id,$amount,$id);
            }else{
                //表示数据库中有该会员的购物车信息,然后判断该信息中是否有选中加入的商品，有，就修改记录amount+1
                if($model2 = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$id])){
                    //表示该会员已添加过该商品，只需要修改纪录即可
                    $model2->amount += $amount;
                    $model2->save();
                }else{
                    //新增
                    $cart->add($goods_id,$amount,$id);
                }
            }


        }
        return $this->redirect(['user/add']);
    }

    public function actionAdd()
    {
        if(\Yii::$app->user->isGuest){
            //取出cookie中的商品id和数量
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie==null){
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            $models = [];
            foreach ($cart as $goods_id => $amount){
                $goods = Goods::findOne(['id'=>$goods_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
        }else{
            $id = \Yii::$app->user->getId();
            $models = [] ;
            //数据库中获取购物车数据
            $cart = Cart::find()->select('*')->where(['member_id'=>$id])->asArray()->all();
//             var_dump($cart);exit;
            foreach ($cart as $v){
                $goods = Goods::findOne(['id'=>$v['goods_id']])->attributes;
                $goods['amount']=$v['amount'];
                $models[] = $goods;

            }
        }
//        var_dump($models,$cart,$cookie);exit;
        return $this->render('add',['models'=>$models]);
    }

    public function actionUpdateCart()
    {
        //接收数据
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }
        //判断是否登录，未登录操作cookie,登录操作数据库
        if(\Yii::$app->user->isGuest){
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                //cookies中没有购物车的数据
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            //将商品的id和数量存到cookie中
            $cookies = \Yii::$app->response->cookies;
            //检查购物车中是否有该商品，有，数量累加
            if(key_exists($goods->id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                if(key_exists($goods['id'],$cart)){
                    unset($cart[$goods_id]);
                }
            }
            $cookie = new Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            //已登录，操作数据库
            $id = \Yii::$app->user->getId();
            $model = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$id]);
            if($amount){
                $model->amount = $amount;
                $model->save();
            }else{
                //删除数据库中对应的购物车记录
                $model->delete();
            }

        }
        return $this->redirect(['user/cart']);
    }


    //购物车结算
    public function actionFlow()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['user/login']);
        }
        $id = \Yii::$app->user->getId();
        //查询出该会员的收货地址信息
        $address = Address::find()->where(['user_id'=>$id])->all();
        //var_dump($address);exit;
        //商品清单
        $models = [] ;
        //数据库中获取购物车数据
        $cart = Cart::find()->select('*')->where(['member_id'=>$id])->asArray()->all();
        // var_dump($cart);exit;
        foreach ($cart as $v){
            $goods = Goods::findOne(['id'=>$v['goods_id']])->attributes;
            $goods['amount']=$v['amount'];
            $models[] = $goods;

        }
        if(\Yii::$app->request->isPost){
            $order = new Order();
//            var_dump(\Yii::$app->request->post());exit;
            $address_id = \Yii::$app->request->post('address_id');
            //查询收货信息
            $address_info = Address::find()->where(['id'=>$address_id])->asArray()->all()[0];
            //var_dump($address_info);exit;

            $order->name = $address_info['name'];
            $order->add_name = $address_info['add_name'];
            $order->tel = $address_info['tel'];
            //查询配送方式
            $delivery_id = \Yii::$app->request->post('delivery_id');
            $order->delivery_id = $delivery_id;
            foreach (Order::$delivery as $v){
                if(($v['id']) == $delivery_id){
                    $order->delivery_name = $v['name'];
                    $order->delivery_price = $v['price'];
                }
            }
            //支付方式
            $payment_id = \Yii::$app->request->post('payment_id');
            $order->payment_id = $payment_id;
            foreach (Order::$payment as $v){
                if($v['id'] == $payment_id){
                    $order->payment_name = $v['name'];
                }
            }
            $total_decimal=\Yii::$app->request->post('total_decimal');
            //总额
            $order->total_decimal = $total_decimal;
            //默认状态为待付款
            $order->status = 1;
            //创建时间
            $order->create_time = time();
            $order->member_id = $id;
            //事务回滚
            $trancaction = \Yii::$app->db->beginTransaction();
            try{
                $order->save();
                //提交
$uid = \Yii::$app->user->id;
                $carts = Cart::find()->where(['member_id'=>$uid])->all();
//                die();
                foreach ($carts as $cart){
                    $goods = Goods::findOne(['id'=>$cart->goods_id,'status'=>1]);
                    if($goods==null){
                        throw  new Exception('商品不存在');
                    }
                    //如果需要的数量大于库存
                    if ($cart->amount > $goods->stock){
                        throw new Exception('商品的数量不够');
                    }
                    $model = new OrderGoods();
                    $model->goods_id = $cart->goods_id;
                    $model->goods_name = $cart->goodsinfo->name;
                    $model->amount = $cart->amount;
                    $model->logo = $cart->goodsinfo->logo;
                    $model->price = $cart->goodsinfo->shop_price;
                    $model->total = $cart->amount*$cart->goodsinfo->shop_price;
                    $model->order_id = $order->id;
                    $model->status = $order->status;
                    $model->save();
                }
                $trancaction->commit();
            }catch (Exception $e){
                $trancaction->rollBack();
                //回滚
            }
            /**
             * ---------order-goods 表
             */
            //清除购物车
            if(Cart::deleteAll(['member_id'=>$id])){
                return $this->redirect('after.html');
            }
        }
        return $this->render('flow',['address'=>$address,'models'=>$models]);
    }

    public function actionAfter()
    {
        return $this->render('after');
    }
    public function actionOrderInfo(){
        $this->layout = 'goods';
        $models = Order::findAll(['member_id'=>\Yii::$app->user->id]);
        //var_dump($models);exit;
        return $this->render('order-info',['models'=>$models]);
    }
    public function actionOrderdel($id){
        $order = OrderGoods::findOne(['id'=>$id]);
        $model = Order::findOne(['id'=>$order->order_id,]);
        $model->status = 0;
        $model->save(false);
        return $this->redirect(['user/order-info']);
    }

    //清除超时未付款的订单
    public function actionClear(){
        $models = Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-3600])->all();
        foreach ($models as $model){
            $model->status = 0;
            $model->save();
            foreach ($model->ordergoods as $good){
                Goods::updateAllCounters(['stock'=>$good->amount,'id'=>$good->goods_id]);
            }
        }
    }
}