<h2>菜单列表</h2>
<?php
echo \yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-info']);
?>
<table class="table">
    <tr>
        <td>id</td>
        <td>名称</td>
        <td>地址/路由</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->label?></td>
        <td><?=$model->url?></td>
        <td><?=$model->sort?></td>
        <td>
            <?php
            echo \yii\bootstrap\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
            echo \yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
            ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>