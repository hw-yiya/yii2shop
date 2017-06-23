<h2>角色列表</h2>
<?php
echo \yii\bootstrap\Html::a('添加',['add-role'],['class'=>'btn btn-info']);
?>
<table class="table">
    <tr>
        <td>名称</td>
        <td>描述</td>
        <td>编辑</td>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td>
            <?php
            echo \yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-info btn-xs']);
            echo \yii\bootstrap\Html::a('删除',['rbac/del-role','name'=>$model->name],['class'=>'btn btn-danger btn-xs']);
            ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>