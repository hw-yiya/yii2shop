<!-- 主体部分 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <!--        <h2 class="fl">--><?//=Html::img('@web/images/logo.png') ?><!--</a></h2>-->
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <form action="" method="post">
        <div class="fillin_bd">
            <!-- 收货人信息  start-->
            <div class="address">
                <h3>收货人信息</h3>
                <div class="address_info">
                    <?php foreach ($address as $add):?>
                        <p><input type="radio" value="<?=$add->id?>" name="address_id" <?=$add->is_default ? 'checked' : ''?>/><?=$add->name.'---'.$add->add_name?> </p>
                    <?php endforeach;?>
                </div>


            </div>
            <!-- 收货人信息  end-->

            <!-- 配送方式 start -->
            <div class="delivery">
                <h3>送货方式 </h3>


                <div class="delivery_select">
                    <table>
                        <thead>
                        <tr>
                            <th class="col1">送货方式</th>
                            <th class="col2">运费</th>
                            <th class="col3">运费标准</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach (\frontend\models\Order::$delivery as $k=>$delivery):?>
                            <tr>
                                <td>
                                    <input type="radio" name="delivery_id" value="<?=$delivery['id']?>" <?=$k ? '': 'checked="checked"' ?>/><?=$delivery['name']?>

                                </td>
                                <td><?=$delivery['price']?></td>
                                <td><?=$delivery['desc']?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>

                </div>
            </div>
            <!-- 配送方式 end -->

            <!-- 支付方式  start-->
            <div class="pay">
                <h3>支付方式 </h3>


                <div class="pay_select">
                    <table>
                        <?php foreach (\frontend\models\Order::$payment as $k=>$payment):?>
                            <tr>
                                <td class="col1"><input type="radio" name="payment_id" value="<?=$payment['id']?>" /><?=$payment['name']?></td>
                                <td class="col2"><?=$payment['desc']?></td>
                            </tr>
                        <?php endforeach;?>
                    </table>

                </div>
            </div>
            <!-- 支付方式  end-->



            <!-- 商品清单 start -->
            <div class="goods">
                <h3>商品清单</h3>
                <table>
                    <thead>
                    <tr>
                        <th class="col1">商品</th>
                        <th class="col3">价格</th>
                        <th class="col4">数量</th>
                        <th class="col5">小计</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($models as $model):?>
                        <tr>
                            <td class="col1"><a href=""><?=\yii\helpers\Html::img($model['logo'])?></a><strong><a href=""><?=$model['name']?></strong></td>
                            <td class="col3"><?=$model['shop_price']?></td>
                            <td class="col4"> <?=$model['amount']?></td>
                            <td class="col5"><span><?=$model['shop_price'] * $model['amount']?></span></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <ul>
                                <!--                            <li>-->
                                <!--                                <span>4 件商品，总商品金额：</span>-->
                                <!--                                <em>￥5316.00</em>-->
                                <!--                            </li>-->
                                <li>
                                    <span>返现：</span>
                                    <em>-￥240.00</em>
                                </li>
                                <li>
                                    <span>运费：</span>
                                    <em>￥10.00</em>
                                </li>
                                <li>
                                    <span>应付总额：</span>
                                    <em>￥<?php $total=0;foreach ($models as $model){
                                            $total += ($model['amount'] * $model['shop_price']);
                                        }echo $total;?>.00</em>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!-- 商品清单 end -->

        </div>

        <div class="fillin_ft">
            <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>" />

            <p>应付总额：<strong><input type="text" readonly name="total_decimal" value="<?php $total=0;foreach ($models as $model){
                        $total += ($model['amount'] * $model['shop_price']);
                    }echo $total;?>"></strong></p>
            <span><input type="submit" value="提交订单"></span>

        </div>
    </form>
</div>
<!-- 主体部分 end -->