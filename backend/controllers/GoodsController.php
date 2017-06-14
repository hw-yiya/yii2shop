<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\Goods_category;
use backend\models\Goods_day_count;
use backend\models\Goods_Intro;
use backend\models\GoodsPhoto;
use backend\models\SearchForm;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodsController extends \yii\web\Controller
{
    //列表页
    public function actionIndex()
    {
        //实例化搜索表单模型
        $model = new SearchForm();
        //只显示状态为1的商品
        $query = Goods::find()->where(['status'=>1]);
        //实例化提交方式
        $request = new Request();
        //判断是否是get方式提交
        if($request->isGet){
            //接受并验证
            if($model->load($request->get()) && $model->validate()){
                //调用search方法
                $model->search($query);
            }
        }
        //实例化分页组件
        $page = new Pagination([
            //查询出数据库所有条数
           'totalCount'=>$query->count(),
            //指定每页显示几条
            'pageSize'=>2,
        ]);
        //查询出数据
        $models = $query->limit($page->limit)->offset($page->offset)->all();
        //将查询后的数据分配到index页面
        return $this->render('index',['models'=>$models,'model'=>$model,'page'=>$page]);
    }
    //添加
    public function actionAdd(){
        $model = new Goods();
        $detail = new Goods_Intro();

        if($model->load(\Yii::$app->request->post())&& $detail->load(\Yii::$app->request->post())&& $model->validate()&& $detail->validate()) {

            $day = date('Y-m-d', time());
            //根据日期查询商品数量表，如果有记录就+1  没有就新增一条
            if (Goods_day_count::find()->select('*')->where(['day' => $day])->count() == 0) {
                //添加新纪录
                $day_count = new Goods_day_count();
                $day_count->day = $day;
                $day_count->count = 1;
                $day_count->save();
            } else {
                //修改记录+1
                $count = Goods_day_count::findOne(['day' => $day]);
                $count->count += 1;
                $count->save();
            }
            //添加时间
            $model->create_time = time();
            //添加货号（新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001）
            //当前年月日
            $ydm = date('Ymd', time());//当前年月日
            //如果没有当天的记录，说明今天还没有添加过商品，创建一条记录，count设置为0；如果有记录，则获取该记录的count值。
            $sum = Goods_day_count::find()->select(['count'])->where(['day' => date('Y-m-d', time())])->column()[0];
            //补0固定五位数
            $sum2 = str_pad($sum, 5, '0', STR_PAD_LEFT);
            //拼接商品货号
            $model->sn = $ydm . '' . $sum2;
            //保存数据
            $model->save();
            $detail->goods_id = $model->id;
            $detail->save();
            \Yii::$app->session->setFlash('success', '添加成功');
            //成功后跳转index页面
            return $this->redirect(['goods/index']);
        }else{
            //失败的话打印错误信息
            //var_dump($model->getErrors(),$detail->getErrors());exit;
        }
        //查询出商品分类列表
        $categories = ArrayHelper::merge([['id' => 0, 'name' => '顶级分类', 'parent_id' => 0]], Goods_category::find()->asArray()->all());
        //将所需数据分配给add视图
        return $this->render('add',['model'=>$model,'detail'=>$detail,'categories'=>$categories]);
    }
    //修改
    public function actionEdit($id){
        //根据id查询数据将其修改
        $model = Goods::findOne(['id'=>$id]);
        $detail = Goods_Intro::findOne(['goods_id'=>$id]);
        //实例化提交方式
        $request = \Yii::$app->request;
        //判断是否post方式提交
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $detail->load($request->post());
            //验证数据
            if($model->validate() && $detail->validate()){
                //得到当前时间戳
                $model->create_time = time();
                //将数据进行保存
                if($model->save()){
                    $detail->save(false);
                }
                //添加成功跳转index页面,失败则打印错误信息
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //查询出商品分类数据列表
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],Goods_category::find()->asArray()->all());
        //将所需数据分配给修改页面
        return $this->render('add',['model'=>$model,'detail'=>$detail,'categories'=>$categories]);
    }
    //逻辑删除(其修改状态)
    public function actionDel($id){
        //根据id修改相关商品的状态
        $model = Goods::findOne(['id'=>$id]);
        $detail = Goods_Intro::findOne(['goods_id'=>$id]);
        //统一将商品状态修改为0并保存数据库
        $model->is_on_sale=0;
        $model->status=0;
        $model->save();
        //跳转index显示页面
        return $this->redirect(['goods/index']);
    }

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl = $action->getWebUrl();
//                    $action->output['fileUrl'] = $action->getWebUrl();
                    //调用七牛云组件，将图片上传到七牛云
                    $qiniu = \Yii::$app->qiniu;
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    //获取该图片在七牛云的地址
                    $url = $qiniu->getLink($imgUrl);
                    $action->output['fileUrl'] = $url;

                    //图片上传成功的同时，将图片和商品关联起来
                    $model = new GoodsPhoto();
                    $model->goods_id = \Yii::$app->request->post('goods_id');
                    $model->path = $action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->path;
                },
            ],
            'upload'=>[
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    //根据Id查看商品详情
    public function actionSel($id){
        //根据id显示相关商品的详情
        $model = Goods::findOne(['id'=>$id]);
        $detail = Goods_Intro::findOne(['goods_id'=>$id]);
        return $this->render('sel',['model'=>$model,'detail'=>$detail]);
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    //添加图片
    public function actionPhoto($id)
    {
        $goodsModels = Goods::findOne($id);
        $photoModel = new GoodsPhoto();
        $request = new Request();
        if ($request->isPost) {
            $photoModel->load($request->post());
            if ($photoModel->validate()) {
                $photoModel->goods_id = $id;
                $photoModel->save();
            }
        }
        return $this->render('photo',['goodsModels'=>$goodsModels,'photoModel'=>$photoModel]);
    }
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }


        return $this->render('gallery',['goods'=>$goods]);

    }

    //删除图片
    public function actionDpt()
    {
        $id = \Yii::$app->request->post('id');
        $model = GoodsPhoto::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }
}
