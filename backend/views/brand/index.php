<h2>品牌列表</h2>
<?php
echo \yii\bootstrap\Html::a('添加品牌',['brand/add'],['class'=>'btn btn-info']);
?>
<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO图片</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><img src="<?=$model->logo?>" width="30px" height="30px"></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\Brand::$brandOption[$model->status]?></td>
            <td>
                <?php
                    echo \yii\bootstrap\Html::a('编辑',['brand/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']) ;
                echo \yii\bootstrap\Html::a('删除',['brand/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']) ;
                ?>
            </td>
        </tr>
    <?php endforeach;;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
]);