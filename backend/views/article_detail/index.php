<h2>文章详情列表</h2>
<table class="table">
    <tr>
        <td>id</td>
        <td>文章分类</td>
        <td>详情</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->article->name?></td>
            <td><?=$model->content?></td>
            <td>
                <?php
                echo \yii\bootstrap\Html::a('编辑',['article_detail/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
                echo \yii\bootstrap\Html::a('删除',['article_detail/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']);
                ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>