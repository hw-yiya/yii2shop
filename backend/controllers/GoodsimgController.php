<?php
namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsImg;
use xj\uploadify\UploadAction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class GoodsimgController extends Controller{

//    public function actionAdd(){
//        $model = new GoodsImg();
//        if($model->load(\Yii::$app->request->post()) && $model->validate()){
//            $model->save(false);
//        }
//    }

   public function actionImg($id){
       $goods = Goods::findOne(['id'=>$id]);

       if($goods == null){
           throw new NotFoundHttpException('商品不存在');
       }
       return $this->render('img',['goods'=>$goods]);
   }
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsImg::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload/logo',
                'baseUrl' => '@web/upload/logo',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "/{$p1}/{$p2}/{$filehash}.{$fileext}";
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
                    //图片上传成功的同时，将图片和商品关联起来

                    $model = new GoodsImg();
                    $qiniu = \Yii::$app->qiniu;
                    $model->goods_id = \Yii::$app->request->post('goods_id');
                    $qiniu->uploadFile($action->getSavePath(),$action->getWebUrl());
//                    //获取该图片的在七牛云的地址
                    $url = $qiniu->getLink($action->getWebUrl());
                    $model->path =$url;
                    $model->save();
                    $action->output['fileUrl']=$url;




//                    //调用七牛云组件,将图片上传到七牛云
//                    $qiniu = \Yii::$app->qiniu;
//                    $qiniu->uploadFile($action->getSavePath(),$action->getWebUrl());
////                    //获取该图片的在七牛云的地址
//                    $url = $qiniu->getLink($action->getWebUrl());
//                    $action->output['fileUrl']=$url;
                },
            ],
        ];
    }
}
