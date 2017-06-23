<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "region".
 *
 * @property string $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $level
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'parent_id', 'level'], 'required'],
            [['id', 'parent_id', 'level'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '省',
            'parent_id' => '市',
            'level' => '区县',
        ];
    }

    public static function getRegion($parentId=0)
    {
        $result = static::find()->where(['parent_id'=>$parentId])->asArray()->all();
        return ArrayHelper::map($result,'id','name');
    }
    //根据id得出地址名称
    public static function getName($id)
    {
        return self::find()->select('name')->where(['id'=>$id])->scalar();
    }
    //得到完整的地区信息
    public static function getFullArea($province,$city,$district)
    {
        return join(' ',[
            self::getName($province),
            self::getName($city),
            self::getName($district),
        ]);
    }
}
