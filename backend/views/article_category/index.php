<h2>文章分类列表</h2>
<?php
echo \yii\bootstrap\Html::a('添加',['article_category/add'],['class'=>'btn btn-info']);
?>
<table class="table">
    <tr>
        <td>ID</td>
        <td>文章分类</td>
        <td>文章简介</td>
        <td>排序</td>
        <td>状态</td>
        <td>类型</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\ArticleCategory::$statusOptions[$model->status]?></td>
            <td><?=\backend\models\ArticleCategory::$is_helpOptions[$model->is_help]?></td>
            <td>
                <?php
                echo \yii\bootstrap\Html::a('编辑',['article_category/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
                echo \yii\bootstrap\Html::a('删除',['article_category/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
                ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
   'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
]);
?>