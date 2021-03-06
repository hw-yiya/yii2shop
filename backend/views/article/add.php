<h2>文章添加</h2>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\ArticleCategory::find()->all(),'id','name'),['prompt'=>'请选择分类']);
echo $form->field($art,'content')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status',['inline'=>true])->radioList([0=>'隐藏',1=>'正常']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();