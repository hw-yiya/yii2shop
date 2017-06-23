<h2>文章列表</h2>
<?php
echo \yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-info']);
?>
<table class="table">
    <tr>
        <td>ID</td>
        <td>文章名</td>
        <td>简介</td>
        <td>文章分类</td>
        <td>排序</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->article_category->name?></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\Article::$statusOptions[$model->status]?></td>
            <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
            <td>
                <?php
                echo \yii\bootstrap\Html::a('查看',['article/sel','id'=>$model->id],['class'=>'btn btn-info btn-xs']);
                echo \yii\bootstrap\Html::a('编辑',['article/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
                echo \yii\bootstrap\Html::a('删除',['article/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
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
])
?>