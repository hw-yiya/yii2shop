<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;


class BrandController extends \yii\web\Controller
{
    //列表
    public function actionIndex()
    {
        //定义方法
        $query =Brand::find();
        //查出数据总条数
        $total = $query->count();
        //每页显示几条
        $page= new Pagination([
           'totalCount'=>$total,
            'defaultPageSize'=>2,
        ]);
        $models = $query->offset($page->offset)->limit($page->limit)->all();
//        var_dump($models);exit;
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    //添加
    public function actionAdd(){
        $model = new Brand();
        $request = \Yii::$app->request;
        //模型接受表单提交的数据
        if($request->isPost){
            //接受表单提交数据
            $model->load($request->post());
            //$model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据并保存数据表
            if($model->validate()){
//                //保存图片
//                $fileName = '/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                $model->logo=$fileName;
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转列表页
                return $this->redirect(['brand/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());exit;
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        //根据id查到对应的数据
        $model = Brand::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        //模型接受表单提交的数据
        if($request->isPost){
            //接受表单提交数据
            $model->load($request->post());
            //$model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据并保存数据表
            if($model->validate()){
//                //保存图片
//                $fileName = '/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                $model->logo=$fileName;
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转列表页
                return $this->redirect(['brand/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());exit;
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //逻辑删除
    public function actionDel($id){
        //根据id查出对应的数据将其修改为删除状态
        $model = Brand::findOne(['id'=>$id]);
        $model->status = -1;
        $model->save(false);
        return $this->redirect(['brand/index']);
    }



    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                'overwriteIfExist' => true,

                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl = $action->getWebUrl();
                    $action->output['fileUrl'] = $action->getWebUrl();
//                    //调用七牛云组件,将图片上传到七牛云
                    $qiniu = \Yii::$app->qiniu;
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
//                    //获取该图片的在七牛云的地址
                    $url = $qiniu->getLink($imgUrl);
                    $action->output['fileUrl']=$url;
                },
            ],
        ];
    }
//    public function actionTest(){
//        $ak = 'E1EhuIzWOns2Y1mEMGgG4KJ5wS8CRZyt1nhdG9tz';
//        $sk = 'FvNQ8tnG07XOQ8SdMZTNxfzuap2kFpDqaBrEyzxv';
//        $domain = 'http://or9qr3rur.bkt.clouddn.com/';
//        $bucket = 'yii2shop';
//        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
//        //要上传的文件
//        $fileName = \Yii::getAlias('@webroot'.'/upload/test.jpg');
//        $key = 'test.jpg';
//        $re = $qiniu->uploadFile($fileName,$key);
//
//        $url = $qiniu->getLink($key);
////        var_dump($url);
//    }
}
