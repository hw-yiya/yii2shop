<table class="table">
    <thead>
    <tr>
        <th width="10%">订单号</th>
        <th width="20%">订单商品</th>
        <th width="10%">收货人</th>
        <th width="20%">订单金额</th>
        <th width="20%">订单状态</th>
        <th width="10%">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model)://一对多就是数组，一对一是对象?>
     <?php //var_dump($model->add);exit;?>
            <tr>
                <td><a href=""><?=$model->order_id?></a></td>
                <td><?=$model->goods_name?></td>
                <td><?=$model->add->name?></td>
                <td>￥<?=$model->total?></td>
                <td><?=\backend\models\OrderGoods::$statusOption[$model->status]?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['ordergoods/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
                    <?=\yii\bootstrap\Html::a('删除',['ordergoods/del','order_id'=>$model->order_id,'id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
                </td>
            </tr>
            <?php endforeach; ?>
    </tbody>
</table>