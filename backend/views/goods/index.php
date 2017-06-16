<h2>商品列表</h2>
<?php
echo \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info']);
?>
<?php
$form=\yii\bootstrap\ActiveForm::begin([
    'method'=>'get',
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
<table class="table">
    <tr>
        <td>id</td>
        <td>商品名称</td>
        <td>货号</td>
        <td>LOGO图片</td>
        <td>商品分类</td>
        <td>品牌分类</td>
        <td>市场价格</td>
        <td>商品价格</td>
        <td>库存</td>
        <td>是否在售</td>
        <td>状态</td>
        <td>排序</td>
        <td>添加时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->sn?></td>
        <td><img src="<?=$model->logo?>" width="30px" height="30px"></td>
        <td><?=$model->goods_category_id?$model->cate_gory->name:''?></td>
        <td><?=$model->brand_id?$model->brand->name:''?></td>
        <td><?=$model->market_price?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>
        <td><?=\backend\models\Goods::$is_on_saleOptions[$model->is_on_sale]?></td>
        <td><?=\backend\models\Goods::$statusOptions[$model->status]?></td>
        <td><?=$model->sort?></td>
        <td><?=date('Y-d-m H:i:s',$model->create_time)?></td>
        <td>
            <?php
            echo \yii\bootstrap\Html::a('查看',['goods/sel','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
            echo \yii\bootstrap\Html::a('添加图片',['goodsimg/img','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
            echo \yii\bootstrap\Html::a('编辑',['goods/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
            echo \yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
            ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
   'pagination'=>$page,
    'nextPageLabel'=>'上一页',
    'prevPageLabel'=>'下一页',

]);
?>