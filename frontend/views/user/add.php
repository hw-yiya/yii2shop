<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/style/cart.css');
$this->registerJsFile('@web/js/cart1.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>
<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($models as $model):?>
            <tr data-goods_id="<?=$model['id']?>">
                <td class="col1"><a href=""><?=\yii\helpers\Html::img($model['logo'])?></a>  <strong><a href=""><?=$model['name']?></a></strong></td>
                <td class="col3">￥<span><?=$model['shop_price']?></span></td>
                <td class="col4">
                    <a href="javascript:;" class="reduce_num"></a>
                    <input type="text" name="amount" value="<?=$model['amount']?>" class="amount"/>
                    <a href="javascript:;" class="add_num"></a>
                </td>

                <td class="col5">￥<span><?=$model['shop_price'] * $model['amount']?></span></td>
                <td class="col6"><a href="javascript:;" class="del_goods">删除</a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total">0</span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <?=\yii\helpers\Html::a('结算',['user/flow'],['class'=>'checkout'])?>
    </div>
</div>
<!-- 主体部分 end -->
<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['user/update-cart']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
         $(".reduce_num,.add_num").click(function() {
          var goods_id = $(this).closest('tr').attr('data-goods_id');
          var amount = $(this).parent().find('.amount').val();
          $.post("$url",{goods_id:goods_id,amount:amount,"_csrf-frontend":"$token"});
        });
        //删除
        $(".del_goods").click(function() {
            if(confirm('是否删除该商品')){
                var goods_id = $(this).closest('tr').attr('data-goods_id');
                $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
                //移除
                $(this).closest('tr').remove();
            }
        })
        
        //总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
JS
))
?>
