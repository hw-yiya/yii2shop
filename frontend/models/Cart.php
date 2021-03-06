<?php

namespace frontend\models;

use backend\models\Goods;
use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $amount
 * @property integer $member_id
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'amount', 'member_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'amount' => 'Amount',
            'member_id' => 'Member ID',
        ];
    }
    public function add($goods_id,$amount,$member_id)
    {
        $this->goods_id = $goods_id;
        $this->amount = $amount;
        $this->member_id = $member_id;

        $this->save();
    }
    public function getGoodsinfo(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
}
