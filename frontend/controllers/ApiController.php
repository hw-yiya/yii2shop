<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\Goods_category;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\PasswdForm;
use frontend\models\Repassword;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller
{

    public $enableCsrfValidation;

    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }

//会员注册  post
    public function actionRegister()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $member = new Member();
            $member->username = $request->post('username');
            $member->password = $request->post('password');
            $member->password_hash = $request->post('password_hash');
            $member->generateAuthKey();
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            $member->code=$request->post('code');
            $member->status = 1;
            if ($member->validate()) {
                $member->save();
                return ['success' => '成功', 'msg' => '', 'data' => $member->toArray()];
            }
            //验证失败
            return ['fail' => '失败', 'msg' => $member->getErrors()];
        }
        return ['fail' => '失败', 'msg' => '请使用post请求'];
    }

//会员登录
    public function actionLogin()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $user = Member::findOne(['username' => $request->post('username')]);
            if ($user && \Yii::$app->security->validatePassword($request->post('password'), $user->password_hash)) {
                \Yii::$app->user->login($user);
                return ['success' => '成功', 'msg' => '登录成功'];
            }
            return ['fail' => '失败', 'msg' => '账号或密码错误'];
        }
        return ['fail' => '失败', 'msg' => '请使用post请求'];
    }

//会员修改密码
    public function actionRePassword()
    {

        if (\Yii::$app->user->isGuest) {
            return ['status' => '-1', 'msg' => '请先登录'];
        }
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $user = Member::findOne(['id' => \Yii::$app->user->getId()]);
            //定义使用的场景
            if ($user && \Yii::$app->security->validatePassword($request->post('password'), $user->password_hash)) {
                $user->password_hash = \Yii::$app->security->generatePasswordHash($request->post('repassword'));
                $user->save();
                //var_dump($user->getErrors());exit;
                return ['success' => '成功', 'msg' => '', 'data' => $user->toArray()];
            }
            //验证失败
            return ['fail' => '失败', 'msg' => $user->getErrors()];
        }
        return ['fail' => '失败', 'msg' => '请使用post请求'];
    }
//获取当前登录用户
    public function actionGetuser()
    {
        if(\Yii::$app->user->isGuest){
            return ['fail'=>'失败','msg'=>'请先登录'];
        }
        return ['success'=>'成功','msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
    }
//添加收货地址
    public function actionAddress(){
        if (\Yii::$app->user->isGuest) {
            return ['status' => '-1', 'msg' => '请先登录'];
        }
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model = new Address();
            $model->user_id = \Yii::$app->user->getId();
            $model->name = $request->post('name');
            $model->add_name = $request->post('add_name');
            $model->tel = $request->post('tel');
            $model->is_default = 1;
            if ($model->validate()) {
                $model->save();
                return ['success' => '成功', 'msg' => '', 'data' => $model->toArray()];
            }
            //验证失败
            return ['fail' => '失败', 'msg' => $model->getErrors()];
        }
        return ['fail' => '失败', 'msg' => '请使用post请求'];
    }
//修改地址
    public function actionUpdateAddress(){
        if (\Yii::$app->user->isGuest) {
            return ['status' => '-1', 'msg' => '请先登录'];
        }
        if($post=\Yii::$app->request->post()){
            $address = Address::findOne(['id'=>$post['id'],'user_id'=>\Yii::$app->user->id]);
//            var_dump($address);exit;
            if ($address) {
//                var_dump();exit;
                $address->name = $post['name'];
                $address->add_name = $post['add_name'];
                $address->tel = $post['tel'];
                if ($address->validate()) {
                    $address->save();
                    return ['success' => '成功', 'msg' => '', 'data' => $address->toArray()];
                }
                //验证失败
                return ['fail' => '失败', 'msg' => $address->getErrors()];
            }
            return ['fail' => '失败', 'msg' => '没有该权限'];

        }
        return ['fail' => '失败', 'msg' => '请使用post请求'];
    }
//删除地址
    public function actionDelAddress(){
        if (\Yii::$app->user->isGuest) {
            return ['status' => '-1', 'msg' => '请先登录'];
        }
        if($post=\Yii::$app->request->post()) {
            $address = Address::findOne(['id' => $post['id'], 'user_id' => \Yii::$app->user->id]);
//            var_dump($address);exit;
            $address->delete();
        }
        return ['success' => '成功', 'msg' => ''];
    }
//地址列表
    public function actionList()
    {
        if (\Yii::$app->user->isGuest) {
            return ['status' => '-1', 'msg' => '请先登录'];
        }
        $id = \Yii::$app->user->id;

        return ['success' => '成功', 'msg' => '','data'=>Address::find()->where(['user_id'=>$id])->asArray()->all()];
    }
//获取所有商品份分类
    public function actionGetCategories(){
        return ['success'=>'成功','msg'=>'','data'=>Goods_category::find()->asArray()->all()];
    }
//获取某分类的所有子分类
    public function actionGetChildren(){
        if($category_id = \Yii::$app->request->get('id')){
            $goodsCategory = Goods_category::findOne(['id'=>$category_id]);
//            var_dump($goodsCategory);exit;
            $lft = $goodsCategory->lft;
            $rgt = $goodsCategory->rgt;
            $tree = $goodsCategory->tree;
            return ['success'=>'成功','msg'=>'','data'=>Goods_category::find()->where(['>','lft',$lft])->andWhere(['<','rgt',$rgt])->andWhere(['tree'=>$tree])->asArray()->all()];
        }
        return ['fail'=>'失败','msg'=>'缺少参数'];
    }
//获取某分类的父分类
    public function actionParentCategory(){
        if($category_id = \Yii::$app->request->get('id')){
            $goodsCategory = Goods_category::findOne(['id'=>$category_id]);
            $parent_id = $goodsCategory->parent_id;
            return ['success'=>'成功','msg'=>'','data'=>Goods_category::find()->where(['id'=>$parent_id])->asArray()->all()];
        }
        return ['fail'=>'失败','msg'=>'缺少参数'];
    }
//获取某分类下的所有商品
    public function actionGetGoodsToCategory()
    {
        if($category_id = \Yii::$app->request->get('id')){
            return ['success'=>'成功','msg'=>'','data'=>Goods::find()->where(['goods_category_id'=>$category_id])->asArray()->all()];
        }
        return ['fail'=>'失败','msg'=>'缺少参数'];
    }
//根据品牌获取商品
    public function actionGetGoodsToBrand()
    {
        if($brand_id = \Yii::$app->request->get('id')){
            return ['success'=>'成功','msg'=>'','data'=>Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all()];
        }
        return ['fail'=>'失败','msg'=>'缺少参数'];
    }
//文章分类列表
    public function actionArticleCategoryList()
    {
        return ['success'=>'成功','msg'=>'','data'=>ArticleCategory::find()->asArray()->all()];
    }
//根据文章分类获取文章
    public function actionGetArticleToCategory()
    {
        if($article_category_id = \Yii::$app->request->get('id')){
            return ['success'=>'成功','msg'=>'','data'=>ArticleCategory::findOne(['id'=>$article_category_id])];
        }
        return ['fail'=>'失败','msg'=>'缺少参数'];
    }
//根据文章获取所属分类
    public function actionGetCategoryToArticle()
    {
        if($article_id = \Yii::$app->request->get('id')){
            return ['success'=>'成功','msg'=>'','data'=>Article::findOne(['id'=>$article_id])];
        }
        return ['fail'=>'失败','msg'=>'缺少参数'];
    }
//购物车添加
    public function actionAddCart()
    {
        $request = \Yii::$app->request;
        if($request->isPost){
            //接收数据
            $goods_id = \Yii::$app->request->post('goods_id');
            $amount = \Yii::$app->request->post('amount');
            $goods = Goods::findOne(['id'=>$goods_id]);
            if($goods == null){
                return ['fail'=>'-1','msg'=>'商品不存在'];
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
                if($cookies->add($cookie)){
                    return ['success'=>'1','msg'=>'购物车添加成功'];
                }
                return ['fail'=>'-1','msg'=>'添加失败'];
            }else{
                //已登录，操作数据库
                //得到登录用户的id
                $id = \Yii::$app->user->getId();
                //先获取数据库中的购物车数据
                $model = Cart::findOne(['member_id'=>$id]);
                //var_dump($goods_id);exit;
                if($model==null){
                    //表示数据库还么有该会员的购物车信息，直接新加一条记录
                    if($cart->add($goods_id,$amount,$id)){
                        return ['status'=>'1','msg'=>'添加成功,此为该会员第一条记录'];
                    }
                }else{
                    //表示数据库中有该会员的购物车信息,然后判断该信息中是否有选中加入的商品，有，就修改记录amount+1
                    if($model2 = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$id])){
                        //表示该会员已添加过该商品，只需要修改纪录即可
                        $model2->amount += $amount;
                        if($model2->save()){
                            return ['success'=>'1','msg'=>'更新会员购物车成功'];
                        }
                        return ['fail'=>'-1','msg'=>$model2->getErrors()];
                    }else{
                        //新增
                        if($cart->add($goods_id,$amount,$id)){
                            return ['success'=>'1','msg'=>'添加会员购物车成功'];
                        }
                        return ['fail'=>'-1','msg'=>$cart->getErrors()];
                    }
                }


            }
        }

        return ['fail'=>'-1','msg'=>'请使用post请求'];
    }
//修改购物车某商品数量
    public function actionUpdateCart()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //接收数据
            $goods_id = $request->post('goods_id');
            $amount = $request->post('amount');
            $goods = Goods::findOne(['id' => $goods_id]);
            if ($goods == null) {
                return ['status' => '-1', 'msg' => '商品不存在'];
            }
            //判断是否登录，未登录操作cookie,登录操作数据库
            if (\Yii::$app->user->isGuest) {
                //先获取cookie中的购物车数据
                $cookies = \Yii::$app->request->cookies;
                $cookie = $cookies->get('cart');
                if ($cookie == null) {
                    //cookiez中没有购物车的数据
                    $cart = [];
                } else {
                    $cart = unserialize($cookie->value);
                }
                //将商品的id和数量存到cookie中
                $cookies = \Yii::$app->response->cookies;
                //检查购物车中是否有该商品，有，修改
                if (key_exists($goods->id, $cart)) {
                    $cart[$goods_id] = $amount;
                } else {
                    return ['status' => '-1', 'msg' => '购物车中无该商品'];
                }
                if ($amount) {
                    $cart[$goods_id] = $amount;
                } else {
                    if (key_exists($goods['id'], $cart)) {
                        unset($cart[$goods_id]);
                    }
                }
                $cookie = new Cookie([
                    'name' => 'cart', 'value' => serialize($cart)
                ]);
                if ($cookies->add($cookie)) {
                    return ['success' => '1', 'msg' => '更新数量成功'];
                }
            } else {
                //已登录，操作数据库
                $id = \Yii::$app->user->getId();
                $model = Cart::findOne(['goods_id' => $goods_id, 'member_id' => $id]);
                if ($model == null) {
                    return ['fail' => '-1', 'msg' => '商品不存在'];
                }
                if ($amount) {
                    $model->amount = $amount;
                    $model->save();
                } else {
                    //删除数据库中对应的购物车记录
                    $model->delete();
                }
                return ['success' => '1', 'msg' => '会员购物车更新成功'];

            }
        }
        return ['fails'=>'-1','msg'=>''];
    }
//删除购物车某商品
    public function actionDeleteCart()
    {
        if($goods_id = \Yii::$app->request->get('goods_id')){
            //判断是否登录，未登录操作cookie,登录操作数据库
            if(\Yii::$app->user->isGuest){
                //先获取cookie中的购物车数据
                $cookies = \Yii::$app->request->cookies;
                $cookie = $cookies->get('cart');
                if($cookie == null){
                    //cookiez中没有购物车的数据
                    $cart = [];
                }else{
                    $cart = unserialize($cookie->value);
                }
                //将商品的id和数量存到cookie中
                $cookies = \Yii::$app->response->cookies;
                //检查购物车中是否有该商品，有，修改
                if(key_exists($goods_id,$cart)){
                    unset($cart[$goods_id]);
                    return ['success'=>'1','msg'=>'删除成功','data'=>Goods::findOne(['id'=>$goods_id])->toArray()];
                }else{
                    return ['fail'=>'-1','msg'=>'购物车中无该商品'];
                }
            }else{
                //已登录，操作数据库
                $id = \Yii::$app->user->getId();
                $model = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$id]);
                if($model == null){
                    return ['fail'=>'-1','msg'=>'商品不存在'];
                }

                if($model->delete()){
                    return ['success'=>'1','msg'=>'会员购物车商品删除成功'];
                }
                return ['fail'=>'-1','msg'=>$model->getErrors()];
            }
        }
        return ['fails'=>'-1','msg'=>'缺少参数'];
    }
//清空购物车
    public function actionCleanCart()
    {
        //判断是否登录，未登录操作cookie,登录操作数据库
        if(\Yii::$app->user->isGuest){
            //先获取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie){
                \Yii::$app->response->cookies->remove($cookie);
                return ['success'=>'1','msg'=>'清空购物车成功'];
            }
        }else{
            //已登录，操作数据库
            $id = \Yii::$app->user->getId();
            if($models = Cart::deleteAll(['member_id'=>$id])){
                return ['success'=>'1','msg'=>'清空会员购物车成功'];
            }

            return ['fail'=>'-1','msg'=>'清空失败'];

        }
        return ['success'=>'1','msg'=>''];
    }
//获取购物车所有商品
    public function actionCartIndex()
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
            // var_dump($cart);exit;
            foreach ($cart as $v){
                $goods = Goods::findOne(['id'=>$v['goods_id']])->attributes;
                $goods['amount']=$v['amount'];
                $models[] = $goods;

            }
        }
        return ['success'=>'1','msg'=>'','data'=>$models];
    }
//获取支付方式
    public function actionGetPayment()
    {
        return ['success'=>'1','msg'=>'','data'=>Order::$payment];
    }
//获取送货方式
    public function actionGetDelivery()
    {
        return ['success'=>'1','msg'=>'','data'=>Order::$delivery];
    }
//提交订单
    public function actionAddOrder()
    {
        if(\Yii::$app->user->isGuest){
            return ['fail'=>'-1','msg'=>'请先登录'];
        }
        $request = \Yii::$app->request;
        if($request->isPost){
            $id = \Yii::$app->user->getId();
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

            $order = new Order();
            $address_id = $request->post('address_id');
            //查询收货信息
            $address_info = Address::find()->where(['id'=>$address_id])->asArray()->all()[0];
            //var_dump($address_info);exit;

            $order->name = $address_info['name'];
            $order->add_name = $address_info['add_name'];
            $order->tel = $address_info['tel'];
            //查询配送方式
            $delivery_id = $request->post('delivery_id');
            $order->delivery_id = $delivery_id;
            foreach (Order::$delivery as $v){
                if(($v['id']) == $delivery_id){
                    $order->delivery_name = $v['name'];
                    $order->delivery_price = $v['price'];
                }
            }
            //支付方式
            $payment_id = $request->post('payment_id');
            $order->payment_id = $payment_id;
            foreach (Order::$payment as $v){
                if($v['id'] == $payment_id){
                    $order->payment_name = $v['name'];
                }
            }
            $total_decimal=$request->post('total_decimal');
            //总额
            $order->total_decimal = $total_decimal;
            //默认状态为待付款
            $order->status = 1;
            //创建时间
            $order->create_time = time();
            $order->member_id = $id;
//            var_dump($order->getErrors());exit;
            if($order->save() && Cart::deleteAll(['member_id'=>$id])){
                return ['status'=>'1','msg'=>'订单生成成功'];
            }
            /**
             * ---------order-goods 表
             */

            return ['fail'=>'-1','msg'=>$order->getErrors()];
        }

    }

//获取当前用户订单列表
    public function actionGetCurrentUserOrder()
    {
        if(\Yii::$app->user->isGuest){
            return ['fail'=>'-1','msg'=>'请先登录'];
        }
        $id = \Yii::$app->user->getId();
        return ['success'=>'1','msg'=>'','data'=>Order::find(['member_id'=>$id])->all()];
    }
//取消订单
    public function actionCleanOrder(){

        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请先登录'];
        }
        $id = \Yii::$app->user->getId();
        if($order_id = \Yii::$app->request->get('order_id')){
            $order = Order::findOne(['id'=>$order_id,'member_id'=>$id]);
            if($order == null){
                return ['status'=>'-1','msg'=>'订单不存在'];
            }
            $order->status = 0;
            if($order->save()){
                return ['status'=>'1','msg'=>'取消成功','data'=>$order->toArray()];
            }
            return ['status'=>'-1','msg'=>'','data'=>$order->getErrors()];
        }
        return ['status'=>'-1','msg'=>'缺少参数'];
    }

//高级Api接口
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>3,
                'maxLength'=>3,
            ],
        ];
    }
    //http://www.yii2shop.com/api/captcha.html 显示验证码
    //http://www.yii2shop.com/api/captcha.html?refresh=1 获取新验证码图片地址
    //http://www.yii2shop.com/api/captcha.html?v=59573cbe28c58 新验证码图片地址
//-文件上传
    public function actionUpload(){
        $img = UploadedFile::getInstanceByName('img');
        if($img){
            $fileName = '/upload/'.uniqid().'.'.$img->extension;
            $result = $img->saveAs(\Yii::getAlias('@webroot').$fileName,0);
            if($result){
                return ['success'=>'1','msg'=>'','data'=>$fileName];
            }
            return ['fail'=>'-1','msg'=>$img->error];
        }
        return ['fail'=>'-1','msg'=>'上传文件为空'];
    }
//分页读取数据
    public function actionGoodsList(){
        //每页显示条数
        $per_page = \Yii::$app->request->get('per_page',2);
        //当前第几页
        $page = \Yii::$app->request->get('page',1);

        $keywords = \Yii::$app->request->get('keywords');

        $page = $page < 1?1:$page;
        $query = Goods::find();
        //总条数
        $total = $query->count();
        //获取当前页的商品数据
        $goods = $query->offset($per_page*($page-1))->limit($per_page)->asArray()->all();
        return ['status'=>'1','msg'=>'','data'=>[
            'total'=>$total,
            'per_page'=>$per_page,
            'page'=>$page,
            'goods'=>$goods
        ]];
    }



//-发送手机验证码
    public function actionSendSms()
    {
        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            return ['fail'=>'-1','msg'=>'电话号码不正确'];
        }
        //检查上次发送时间是否超过1分钟
        $value = \Yii::$app->cache->get('time_tel_'.$tel);
        $s = time()-$value;
        if($s <60){
            return ['fail'=>'-1','msg'=>'请'.(60-$s).'秒后再试'];
        }

        $code = rand(1000,9999);
        //$result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        $result = 1;
        if($result){
            //保存当前验证码 session  mysql  redis  不能保存到cookie
//            \Yii::$app->session->set('code',$code);
//            \Yii::$app->session->set('tel_'.$tel,$code);
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            \Yii::$app->cache->set('time_tel_'.$tel,time(),5*60);
            //echo 'success'.$code;
            return ['success'=>'1','msg'=>''];
        }else{
            return ['fail'=>'-1','msg'=>'短信发送失败'];
        }
    }

}

