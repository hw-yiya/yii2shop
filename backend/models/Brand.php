<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    public $imgFile;
    public $code;
    //品牌状态
    static public $brandOption=[-1=>'删除',0=>'隐藏',1=>'正常'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        //设置字段验证规则
        return [
            [['name'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            [['code'],'captcha'],
            //['imgFile','file','extensions'=>['gif','png','jpg'],'skipOnEmpty'=>false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        //设置属性的标签名称
        return [
            'id' => 'ID',
            'name' => '品牌名',
            'intro' => '简介',
            'logo' => 'LOGO图片',
            'sort' => '排序',
            'status' => '状态',
            'code'=>'验证码',
        ];
    }
}
