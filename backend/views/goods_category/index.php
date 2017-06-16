<h2>商品分类列表</h2>
<?php
echo \yii\bootstrap\Html::a('添加',['goods_category/add'],['class'=>'btn btn-info']);
?>
<table class="cate table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
        <tr data-lft="<?=$model->lft?>" data-rgt="<?=$model->rgt?>" data-tree="<?=$model->tree?>">
            <td><?=$model->id?></td>
            <td><?=str_repeat('— ',$model->depth).$model->name?>
                <span class="down glyphicon glyphicon-hand-down" style="float:right"></span>
            </td>
            <td>
                <?php
                echo \yii\bootstrap\Html::a('修改',['goods_category/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs']);
                ?>
            </td>
        </tr>
    <?php endforeach;?>,.
</table>
<?php
$js = <<<JS
    $(".down").click(function(){
        //查找当前分类的子孙分类
        var tr = $(this).closest('tr');
        var tree = parseInt(tr.attr('data-tree'));
        var lft = parseInt(tr.attr('data-lft'));
        var rgt = parseInt(tr.attr('data-rgt'));
        //显示显示隐藏
        var show = $(this).hasClass('glyphicon glyphicon-hand-left');
        //切换图标
        $(this).toggleClass('glyphicon glyphicon-hand-down');
        $(this).toggleClass('glyphicon glyphicon-hand-left');
        $(".cate tr").each(function () {
            //同一棵树 左值大于lft 右值小于rgt
            console.log($(this).attr('data-tree'));
            if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft'))>lft && parseInt($(this).attr('data-rgt'))<rgt){
            show ?$(this).show():$('this').hide();
            }
        });
    });


JS;
$this->registerJs($js);
?>