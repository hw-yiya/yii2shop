<?php

namespace backend\models;

use frontend\models\Order;
use Yii;

/**
 * This is the model class for table "order_goods".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property string $logo
 * @property string $price
 * @property integer $amount
 * @property string $total
 */
class OrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'amount','status'], 'integer'],
            [['price', 'total'], 'number'],
            [['goods_name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单id',
            'goods_id' => '商品id',
            'goods_name' => '商品名称',
            'logo' => '商品logo',
            'price' => '价格',
            'amount' => '数量',
            'total' => '总计',
            'status' => '状态',
        ];
    }
    public static $statusOption = [0=>'已取消',1=>'待发货',2=>'已送货',3=>'待收货'];
    //订单和订单商品关系
//    public function getOrders()
//    {
//        return $this->hasMany(OrderGoods::classname(),['id'=>'order_id']);
//    }
    public function getAdd(){
        return $this->hasOne(Order::className(),['id'=>'order_id']);
    }
}
