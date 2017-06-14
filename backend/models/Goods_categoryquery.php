<?php
namespace backend\models;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;


class Goods_categoryquery extends ActiveQuery{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}