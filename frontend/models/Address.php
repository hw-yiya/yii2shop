<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $add_name
 * @property integer $tel
 * @property integer $is_default
 */
class Address extends \yii\db\ActiveRecord
{
    public $province;  //省份
    public $city;       //城市
    public $district;        //区县
    public $add_detail;  //详细地址
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tel', 'is_default'], 'integer'],
//            [['tel','province','city','district',], 'required'],
            [['name'], 'string', 'max' => 30],
            [['add_detail'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'name' => '收货人',
            'add_name' => '收货地址',
            'tel' => '手机号码',
            'is_default' => '是否默认',
            'add_detail' => '详细地址',
        ];
    }
}
