<h2>文章详情添加</h2>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'article_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Article::find()->all(),'id','name'));
echo $form->field($model,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();