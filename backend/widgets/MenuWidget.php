<?php
namespace backend\widgets;

use backend\models\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use Yii;

class MenuWidget extends Widget{
    //widget被实例化后执行的代码
    public function init()
    {
        parent::init();
    }

    //widget被调用时，需要执行的代码
    public function run()
    {
        NavBar::begin([
            'brandLabel' => 'My Company',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => '首页', 'url' => ['/site/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => ['/admin/login']];
        }else {
                $menuItems[] = ['label' => '注销('.Yii::$app->user->identity->username.')', 'url' => ['admin/logout']];
            $menuItems[] = ['label' => '修改密码', 'url' => ['/admin/pas']];
            //根据用户的权限显示菜单
            /*$menuItems[] = ['label'=>'用户管理','items'=>[
                ['label'=>'添加用户','url'=>['admin/add']],
                ['label'=>'用户列表','url'=>['admin/index']]
            ]];*/
            //获取所有一级菜单
            $menus = Menu::findAll(['parent_id'=>0]);
            foreach ($menus as $menu){
                $item = ['label'=>$menu->label,'items'=>[]];
                foreach ($menu->children as $child){
                    //根据用户权限判断，该菜单是否显示
                    if(Yii::$app->user->can($child->url)){
                        $item['items'][] = ['label'=>$child->label,'url'=>[$child->url]];
                    }

                }
                //如果该一级菜单没有子菜单，就不显示
                if(!empty($item['items'])){
                    $menuItems[] = $item;
                }

            }


        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
}